<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\Plan_task;
use App\Models\Reflection_question;
use App\Models\Reflection_answer;
use App\Models\Plan_goal;
use App\Models\Plan;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use DateTimeImmutable;
use DateInterval;
use App\Models\Reminder;

class ReflectionController extends Controller
{
  public function showReflection()
  {
    $questions = Reflection_question::orderBy('number')->get();
    $answers = Reflection_answer::all();
    $plans = Plan::where('fk_user', auth()->user()->id)->where('active', '=', 1)->where('title', '!=', 'Refleksija')->whereNotIn('title', ['Iššūkis'])->with('getPlanHabits.habits')->get();
    $habits = $plans->pluck('getPlanHabits')->flatten()->pluck('habits')->flatten()->toArray();
    $filteredHabits = collect($habits)->reject(function ($habit) {
      return $habit['status'] === 'completed';
    })->toArray();
    return inertia::render('Reflection', [
      'questionData' => $questions,
      'answerData' => $answers,
      'habitData' => $filteredHabits
    ]);
  }
  public function reflectionFinished(Request $request)
  {
    $plans = Plan::where('fk_user', auth()->user()->id)->where('active', '=', 1)->where('title', '!=', 'Refleksija')->whereNotIn('title', ['Iššūkis'])->with('getPlanHabits.habits')->get();
    $habits = $plans->pluck('getPlanHabits')->flatten()->pluck('habits')->flatten()->toArray();
    foreach ($request->planAnswers as $key => $answer) {
      if ($answer == 1) {
        $id = $key;
        $habit = $habits[$id];
        $habitToUpdate = Habit::find($habit['id']);
        $habitToUpdate->status = 'completed';
        $habitToUpdate->save();
      }
    }
    // Check if all plan habits are completed
    $plansTemp = Plan::where('fk_user', auth()->user()->id)->where('active', '=', 1)->where('title', '!=', 'Refleksija')->whereNotIn('title', ['Iššūkis'])->with('getPlanHabits.habits')->get();
    foreach ($plansTemp as $plan) {
      $planCompleted = true;
      foreach ($plan->getPlanHabits as $planHabit) {
        if ($planHabit->habits->status !== 'completed') {
          $planCompleted = false;
          break;
        }
      }
      if ($planCompleted) {
        error_log("yes");
        $plan->active = 2;
        $plan->save();
        $goals = Plan_goal::where('fk_plan', $plan->id)->get();
        foreach ($goals as $goal) {
          if ($goal->goals->status != 'completed') {
            if ($goal->goals->plan_goal->count() > 1) {
              $keepActive = false;
              foreach ($goal->goals->plan_goal as $plan_goal) {
                if ($plan_goal->plan->active == 1) {
                  $keepActive = true;
                  break;
                }
              }
              if ($keepActive) {
                $goal->goals->status = 'in progress';
              } else {
                $goal->goals->status = 'completed';
              }
              $goal->goals->save();
            } else {
              $goal->goals->status = 'completed';
              $goal->goals->save();
            }
          }
        }
      } else {
        error_log("no");
        //getting tasks for each day
        $tasksByWeekday = array(
          'Monday' => array(),
          'Tuesday' => array(),
          'Wednesday' => array(),
          'Thursday' => array(),
          'Friday' => array(),
          'Saturday' => array(),
          'Sunday' => array(),
        );

        foreach ($plan->getTasks->where('execution_date', '>', Carbon::now()) as $task) {
          $weekday = date('l', strtotime($task['execution_date']));
          $time = date('H:i', strtotime($task['execution_date']));
          $existingTask = null;

          // Look for existing task with same title, time and weekday
          foreach ($tasksByWeekday[$weekday] as $existing) {
            if (
              $existing['title'] === $task['title'] &&
              date('H:i', strtotime($existing['execution_date'])) === $time
            ) {
              $existingTask = $existing;
              break;
            }
          }
          if (!$existingTask) {
            // Add task to weekday array
            if ($task->getTask->title !== "Refleksija")
              $tasksByWeekday[$weekday][] = $task;
          }
        }
        $convertedArray = [];
        foreach ($tasksByWeekday as $day => $tasks) {
          $convertedTasks = [];
          foreach ($tasks as $task) {
            $convertedTasks[] = $task->toArray();
          }
          $convertedArray[$day] = $convertedTasks;
        }
        $tasksByWeekday = $convertedArray;

        //check if reminder is needed
        $reminderNeeded = false;
        foreach ($tasksByWeekday as $day => $tasks) {
          foreach ($tasks as $task) {
            if ($task['fk_reminder'] === null) {
              $reminderNeeded = false;
              break 2;
            }
            $reminderNeeded = true;
            break 2;
          }
        }
        //find all plan tasks unique
        $uniqueTasks = array();
        foreach ($plan->getTasks->where('execution_date', '>', Carbon::now()) as $task) {
          $title = $task->getTask;
          if ($title->title !== "Refleksija" && !in_array($title, $uniqueTasks)) {
            $uniqueTasks[] = $task->getTask;
          }
        }

        $convertedTaskArray = [];
        foreach ($uniqueTasks as $index => $task) {
          $convertedTaskArray[$index] = $task->toArray();
        }
        $uniqueTasks = $convertedTaskArray;

        //deleting only upcoming tasks except reflection
        foreach ($plan->getTasks->where('execution_date', '>', Carbon::now()) as $task) {
          if ($task->getTask->title !== "Refleksija") {
            $task->delete();
          }
        }
        //TODO: delete reminders
        // //deleting all reminders if existing
        // foreach ($plan->getTasks as $task) {
        //   if ($task->fk_reminder != null) {
        //     $task->getReminder->delete();
        //   }
        // }

        //adding tasks and reminders(if existing)
        foreach ($uniqueTasks as $task) {
          $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
          foreach ($daysOfWeek as $day) {
            $taskTime = null;

            //check if task is in $day array
            foreach ($tasksByWeekday[$day] as $checkDay) {
              if (in_array($task['title'], $checkDay['get_task'])) {
                $taskTime = $checkDay['execution_date'];
                break;
              }
            }
            if ($taskTime) {
              $dateTime = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $taskTime);
              $hoursAndMinutes = $dateTime->format('H:i');
              for ($i = 0; $i < 4; $i++) {
                $upcomingDay = new DateTimeImmutable('next ' . ucfirst($day) . ' +' . $i . ' week');
                $tempDate = $upcomingDay->format('Y-m-d') . ' ' . $hoursAndMinutes;
                if ($reminderNeeded != false) {
                  $newReminder = new Reminder();
                  $newReminder->remind_time = DateTimeImmutable::createFromFormat('Y-m-d H:i', $tempDate);
                  $newReminder->save();
                }
                $newPlanTask = new Plan_task();
                $newPlanTask->execution_date = DateTimeImmutable::createFromFormat('Y-m-d H:i', $tempDate);
                $newPlanTask->is_done = 0;
                if ($reminderNeeded != false) {
                  $newPlanTask->fk_reminder = $newReminder->id;
                } else {
                  $newPlanTask->fk_reminder = null;
                }
                $newPlanTask->fk_task = $task['id'];
                $newPlanTask->fk_plan = $plan->id;
                $newPlanTask->save();
              }
            }
          }
        }
      }
    }

    $existingPlan = Plan::where('title', 'Refleksija')->where('fk_user', auth()->user()->id)->first();
    if (!$existingPlan) {
      $plan = new Plan();
      $plan->title = "Refleksija";
      $plan->active = 1;
      $plan->color = "#bae7ff";
      $plan->fk_user = auth()->user()->id;
      $plan->save();
    }
    else{
      $plan = $existingPlan;
    }

    $existingTask=Task::where('title', 'Refleksija')->first();
    if(!$existingTask){
      $task = new Task();
      $task->duration = 15;
      $task->title = "Refleksija";
      $task->save();
    }
    else{
      $task=$existingTask;
    }
    //adding reflection as a task
    $reflection = new Plan_task();
    $reflection->fk_plan = $plan->id;
    $reflection->fk_task = $task->id;
    $reflection->execution_date = Carbon::now('Europe/Vilnius');
    $reflection->is_done = 1;
    $reflection->save();
    return Redirect::route('Schedule');
  }
}
