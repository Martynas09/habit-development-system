<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Plan;
use App\Models\Plan_task;
use App\Models\Task;
use App\Models\Prize;
use App\Models\Goal;
use App\Models\Habit;
use App\Models\Plan_goal;
use App\Models\Plan_habit;
use App\Models\Reminder;
use DateTimeImmutable;
use DateInterval;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class PlanController extends Controller
{
  public function showPlans()
  {
    $plans = Plan::where('fk_user', auth()->user()->id)
      ->where('title', 'not like', '%iššūkis%')
      ->where('title', '!=', 'Refleksija')
      ->orderBy('created_at', 'desc')
      ->get();
    return inertia::render('Plan/PlanListView', [
      'plans' => $plans
    ]);
  }

  public function showAlternatives()
  {
    return inertia::render('Plan/ChooseAlternativeView');
  }
  public function showQuestionnaire()
  {
    return inertia::render('Plan/QuestionnaireView');
  }
  public function showCustom()
  {
    $goals = Goal::where('status', '!=', 'completed')->where('fk_user', auth()->user()->id)->get();
    return inertia::render('Plan/CustomView', [
      'goals' => $goals
    ]);
  }
  public function createPlan(Request $request)
  {
    $request->validate([
      'title' => 'required',
      'color' => 'required',
      'tasks' => 'required',
      'reminder' => 'required',
      'goals' => 'required',
      'habits' => 'required',
    ]);
    $goals = [];
    $habits = [];
    $tasks = [];
    $plan = new Plan();
    $plan->fk_user = auth()->user()->id;
    $plan->title = $request->title;
    $plan->color = $request->color;
    $plan->active = 1;
    $plan->save();
    foreach ($request->tasks as $task) {
      $newTask = new Task();
      $newTask->title = $task['value'];
      $newTask->duration = $task['duration'];
      $newTask->save();
      $tasks[$newTask->id] = $newTask;
      $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
      foreach ($daysOfWeek as $day) {
        $taskTime = null;

        //check if task is in $day array
        foreach ($request->$day as $checkDay) {
          if (in_array($task['value'], $checkDay)) {
            $taskTime = $checkDay['time'];
            break;
          }
        }
        if ($taskTime) {
          $dateTime = DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s.u\Z', $taskTime)
            ->add(new DateInterval('PT3H'));
          $hoursAndMinutes = $dateTime->format('H:i');
          for ($i = 0; $i < 4; $i++) {
            $upcomingDay = new DateTimeImmutable('next ' . ucfirst($day) . ' +' . $i . ' week');
            $tempDate = $upcomingDay->format('Y-m-d') . ' ' . $hoursAndMinutes;
            if ($request->reminder == 'system') {
              $newReminder = new Reminder();
              $newReminder->remind_time = DateTimeImmutable::createFromFormat('Y-m-d H:i', $tempDate);
              $newReminder->save();
            }
            $newPlanTask = new Plan_task();
            $newPlanTask->execution_date = DateTimeImmutable::createFromFormat('Y-m-d H:i', $tempDate);
            $newPlanTask->is_done = 0;
            if ($request->reminder == 'system') {
              $newPlanTask->fk_reminder = $newReminder->id;
            } else {
              $newPlanTask->fk_reminder = null;
            }
            $newPlanTask->fk_task = $newTask->id;
            $newPlanTask->fk_plan = $plan->id;
            $newPlanTask->save();
          }
        }
      }
    }
    foreach ($request->goals as $goal) {
      $tempGoal = Goal::where('title', $goal['value'])->where('fk_user', auth()->user()->id)->first();
      if ($tempGoal) {
        $newGoal = $tempGoal;
        $newGoal->status = 'in progress';
        $newGoal->save();
      } else {
        $newGoal = new Goal();
        $newGoal->title = $goal['value'];
        $newGoal->status = 'in progress';
        $newGoal->fk_user = auth()->user()->id;
        $newGoal->save();
      }
      $newPlanGoal = new Plan_goal();
      $newPlanGoal->fk_plan = $plan->id;
      $newPlanGoal->fk_goal = $newGoal->id;
      $newPlanGoal->save();
      $goals[$newGoal->id] = $newGoal;
    }
    foreach ($request->habits as $habit) {
      $newHabit = new Habit();
      $newHabit->title = $habit['value'];
      $newHabit->status = 'in progress';
      $newHabit->fk_user = auth()->user()->id;
      $newHabit->save();
      $newPlanHabit = new Plan_habit();
      $newPlanHabit->fk_plan = $plan->id;
      $newPlanHabit->fk_habit = $newHabit->id;
      $newPlanHabit->save();
      $habits[$newHabit->id] = $newHabit;
    }
    foreach ($request->prizes as $prize) {
      $newPrize = new Prize();
      $newPrize->title = $prize['title'];
      $newPrize->category = $prize['category'];
      if ($prize['category'] == 'goal') {
        foreach ($goals as $goal) {
          if ($goal['title'] == $prize['receiverTitle']) {
            $newPrize->fk_goal = $goal['id'];
            break;
          }
        }
      }
      if ($prize['category'] == 'habit') {
        foreach ($habits as $habit) {
          if ($habit['title'] == $prize['receiverTitle']) {
            $newPrize->fk_habit = $habit['id'];
            break;
          }
        }
      }
      if ($prize['category'] == 'task') {
        foreach ($tasks as $task) {
          if ($task['title'] == $prize['receiverTitle']) {
            $newPrize->fk_task = $task['id'];
            break;
          }
        }
      }

      $newPrize->fk_plan = $plan->id;
      $newPrize->save();
    }
    return Inertia::render('Plan/CustomView');
  }
  public function showPlanEdit($id)
  {
    $existingGoals = Goal::where('status', '!=', 'completed')
      ->where('fk_user', auth()->user()->id)
      ->where(function ($query) use ($id) {
        $query->whereHas('plan_goal', function ($subQuery) use ($id) {
          $subQuery->where('fk_plan', '!=', $id);
        })->orWhereDoesntHave('plan_goal');
      })
      ->get();
    $goals = array();
    $habits = array();
    $tasks = array();
    $prizes = array();
    $plan = Plan::find($id);
    $reminders = false;

    //getting goals
    $goalsTemp = Plan_goal::where('fk_plan', $plan->id)->get()->load('goals');
    foreach ($goalsTemp as $goal) {
      $goals[] = $goal->goals;
    }

    //getting habits
    $habitsTemp = Plan_habit::where('fk_plan', $plan->id)->get()->load('habits');
    foreach ($habitsTemp as $habit) {
      $habits[] = $habit->habits;
    }

    //getting tasks list
    foreach ($plan->getTasks->where('execution_date', '>', Carbon::now()) as $task) { //HERE
      if ($task->fk_reminder != null) {
        $reminders = true;
      }
      $title = $task->getTask;
      if ($title->title !== "Refleksija" && !in_array($title, $tasks)) {
        $tasks[] = $task->getTask;
      }
    }

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

    foreach ($plan->getTasks->where('execution_date', '>', Carbon::now()) as $task) { //HERE
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

    //getting prizes list
    $prizesTemp = Prize::where('fk_plan', $plan->id)->get()->load('plan', 'task', 'habit', 'goal');
    foreach ($prizesTemp as $prize) {
      $prizes[] = $prize;
    }


    return inertia::render('Plan/PlanEditView', [
      'plan' => $plan,
      'goals' => $goals,
      'habits' => $habits,
      'tasks' => $tasks,
      'tasksByWeekday' => $tasksByWeekday,
      'prizes' => $prizes,
      'reminders' => $reminders,
      'existingGoals' => $existingGoals,
    ]);
  }
  public function editPlan(Request $request, $id)
  {
    $request->validate([
      'title' => 'required',
      'color' => 'required',
      'tasks' => 'required',
      'goals' => 'required',
      'habits' => 'required',
    ]);
    $goals = [];
    $habits = [];
    $tasks = [];
    $plan = Plan::find($id);
    $plan->title = $request->title;
    $plan->color = $request->color;
    $plan->save();
    //TODO CLEAR REMINDERS AND TASKS
    //deleting all reminders
    // foreach ($plan->getTasks->where('execution_date', '>', Carbon::now()) as $task) {
    //   if ($task->fk_reminder != null) {
    //     $task->getReminder->delete();
    //   }
    // }

    //deleting only upcoming tasks
    // foreach ($plan->getTasks->where('execution_date', '>', Carbon::now())->load('getTask') as $task) {
    //   $task->getTask->delete();
    // }
    foreach ($plan->getTasks->where('execution_date', '>', Carbon::now()->addDays(1)) as $task) {
      $task->delete();
    }

    //adding tasks and reminders(if selected system)
    foreach ($request->tasks as $task) {
      $newTask = new Task();
      $newTask->title = $task['value'];
      $newTask->duration = $task['duration'];
      $newTask->save();
      $tasks[$newTask->id] = $newTask;
      $daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
      foreach ($daysOfWeek as $day) {
        $taskTime = null;

        //check if task is in $day array
        foreach ($request->$day as $checkDay) {
          if (in_array($task['value'], $checkDay)) {
            $taskTime = $checkDay['time'];
            break;
          }
        }
        if ($taskTime) {
          $dateTime = DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s.u\Z', $taskTime)
            ->add(new DateInterval('PT3H'));
          $hoursAndMinutes = $dateTime->format('H:i');
          for ($i = 0; $i < 4; $i++) {
            $upcomingDay = new DateTimeImmutable('next ' . ucfirst($day) . ' +' . $i . ' week');
            $tempDate = $upcomingDay->format('Y-m-d') . ' ' . $hoursAndMinutes;
            if ($request->reminder == 'system') {
              $newReminder = new Reminder();
              $newReminder->remind_time = DateTimeImmutable::createFromFormat('Y-m-d H:i', $tempDate);
              $newReminder->save();
            }
            $newPlanTask = new Plan_task();
            $newPlanTask->execution_date = DateTimeImmutable::createFromFormat('Y-m-d H:i', $tempDate);
            $newPlanTask->is_done = 0;
            if ($request->reminder == 'system') {
              $newPlanTask->fk_reminder = $newReminder->id;
            } else {
              $newPlanTask->fk_reminder = null;
            }
            $newPlanTask->fk_task = $newTask->id;
            $newPlanTask->fk_plan = $plan->id;
            $newPlanTask->save();
          }
        }
      }
    }
    //removing all plan_goals
    $plan->getPlanGoals()->delete();

    //adding only new goals, and creating new plan_goals
    foreach ($request->goals as $goal) {
      // Check if a goal with the same title already exists in the database
      $existingGoal = Goal::where('title', $goal['value'])
        ->where('fk_user', auth()->user()->id)
        ->orderBy('created_at', 'desc')
        ->first();

      // If the goal doesn't exist, create a new one
      if (!$existingGoal) {
        $newGoal = new Goal();
        $newGoal->title = $goal['value'];
        $newGoal->status = 'in progress';
        $newGoal->fk_user = auth()->user()->id;
        $newGoal->save();
      } else {
        $newGoal = $existingGoal;
        $newGoal->status = 'in progress';
        $newGoal->save();
      }
      $newPlanGoal = new Plan_goal();
      $newPlanGoal->fk_plan = $plan->id;
      $newPlanGoal->fk_goal = $newGoal->id;
      $newPlanGoal->save();
      $goals[$newGoal->id] = $newGoal;
    }

    //removing all plan_habits
    $plan->getPlanHabits()->delete();

    //adding only new habits, and creating new plan_habits
    foreach ($request->habits as $habit) {
      // Check if a habit with the same title already exists in the database
      $existingHabit = Habit::where('title', $habit['value'])->where('fk_user', auth()->user()->id)->orderBy('created_at', 'desc')->first();

      // If the habit doesn't exist, create a new one
      if (!$existingHabit) {
        $newHabit = new Habit();
        $newHabit->status = 'in progress';
        $newHabit->title = $habit['value'];
        $newHabit->fk_user = auth()->user()->id;
        $newHabit->save();
      } else {
        $newHabit = $existingHabit;
      }

      $newPlanHabit = new Plan_habit();
      $newPlanHabit->fk_plan = $plan->id;
      $newPlanHabit->fk_habit = $newHabit->id;
      $newPlanHabit->save();
      $habits[$newHabit->id] = $newHabit;
    }

    //deleting all prizes
    $plan->getPrizes()->delete();

    //adding new ones
    foreach ($request->prizes as $prize) {
      $newPrize = new Prize();
      $newPrize->title = $prize['title'];
      $newPrize->category = $prize['category'];
      if ($prize['category'] == 'goal') {
        foreach ($goals as $goal) {
          if ($goal->title == $prize['receiverTitle']) {
            $newPrize->fk_goal = $goal->id;
          }
        }
      }
      if ($prize['category'] == 'habit') {
        foreach ($habits as $habit) {
          if ($habit->title == $prize['receiverTitle']) {
            $newPrize->fk_habit = $habit->id;
          }
        }
      }
      if ($prize['category'] == 'task') {
        foreach ($tasks as $task) {
          if ($task->title == $prize['receiverTitle']) {
            $newPrize->fk_task = $task->id;
          }
        }
      }
      $newPrize->fk_plan = $plan->id;
      $newPrize->save();
    }



    return Inertia::render('Plan/PlanEditView');
  }
  public function deletePlan(Request $request)
  {
    $plan = Plan::find($request->id);
    //deleting all reminders
    foreach ($plan->getTasks as $task) {
      if ($task->fk_reminder != null) {
        $task->getReminder->delete();
      }
    }

    //deleting all tasks
    foreach ($plan->getTasks->load('getTask') as $task) {
      $task->getTask->delete();
    }

    //deleting all habits
    foreach ($plan->getPlanHabits->load('habits') as $habit) {
      $habit->habits->delete();
    }
    //deleting all goals if they dont have other plans
    foreach ($plan->getPlanGoals->load('goals') as $goal) {
      if ($goal->goals->status != 'completed') {
        if ($goal->goals->plan_goal->count() == 1) {
          $goal->goals->delete();
        }
      }
    }
    $plan->getTasks()->delete();
    $plan->getPlanGoals()->delete();
    $plan->getPlanHabits()->delete();
    $plan->getPrizes()->delete();
    $plan->delete();
  }
  public function planUpdateActive(Request $request)
  {
    $plan = Plan::find($request->id);
    $plan->active = !$request->active;
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
            $goal->goals->status = 'not in progress';
          }
          $goal->goals->save();
        } else {
          if ($plan->active == 1) {
            $goal->goals->status = 'in progress';
          } else {
            $goal->goals->status = 'not in progress';
          }
          $goal->goals->save();
        }
      }
    }
  }
  public function showRecommended()
  {
    $plans = Plan::where('fk_user', 2)->get();
    return inertia::render('Plan/RecommendedView', [
      'plans' => $plans
    ]);
  }
  public function selectRecommended(Request $request)
  {
    $goalsArray = [];
    $habitsArray = [];
    $tasksArray = [];
    $tempTaskArray = [];
    $originalPlan = Plan::find($request->id);
    $newPlan = new Plan;
    $newPlan->title = 'Pavadinimas';
    $newPlan->active = 1;
    $newPlan->color = 'white';
    $newPlan->fk_user = auth()->user()->id;
    $newPlan->save();

    foreach ($originalPlan->getTasks as $task) {
      $tempTaskArray[] = $task->getTask;
    }
    $tasks = array_unique($tempTaskArray);
    //tasks plan_tasks
    foreach ($tasks as $task) {
      $newTask = new Task;
      $newTask->title = $task->title;
      $newTask->duration = $task->duration;
      $newTask->save();
      $tasksArray[$task->id] = $newTask->id;
      $newPlanTask = new Plan_task;
      $newPlanTask->execution_date = Carbon::now()->addDays(1);
      $newPlanTask->is_done = 0;
      $newPlanTask->fk_task = $newTask->id;
      $newPlanTask->fk_plan = $newPlan->id;
      $newPlanTask->fk_reminder = null;
      $newPlanTask->save();
      // foreach ($task->getPlanTasks as $planTask) {
      //   $newReminder = new Reminder();
      //   $newReminder->remind_time = $planTask->execution_date;
      //   $newReminder->save();
      //   $newPlanTask = new Plan_task;
      //   $newPlanTask->execution_date = Carbon::now()->addDays(1);
      //   $newPlanTask->is_done = 0;
      //   $newPlanTask->fk_task = $newTask->id;
      //   $newPlanTask->fk_plan = $newPlan->id;
      //   $newPlanTask->fk_reminder = null;
      //   $newPlanTask->save();
      // }
    }
    //goals and plan_goals
    foreach ($originalPlan->getPlanGoals as $plangoals) {
      $existingGoal = Goal::where('title', $plangoals->goals->title)
        ->where('fk_user', auth()->user()->id)
        ->where('status', '!=', 'completed')
        ->first();
      if (!$existingGoal) {
        $newGoal = new Goal();
        $newGoal->title = $plangoals->goals->title;
        $newGoal->status = 'in progress';
        $newGoal->fk_user = auth()->user()->id;
        $newGoal->save();
      } else {
        $newGoal = $existingGoal;
        $newGoal->status = 'in progress';
        $newGoal->save();
      }
      $newPlanGoal = new Plan_goal();
      $newPlanGoal->fk_plan = $newPlan->id;
      $newPlanGoal->fk_goal = $newGoal->id;
      $newPlanGoal->save();
      $goalsArray[$plangoals->goals->id] = $newGoal->id;
    }

    //habits and plan_habits
    foreach ($originalPlan->getPlanHabits as $planhabits) {
      $newHabit = new Habit();
      $newHabit->title = $planhabits->habits->title;
      $newHabit->status = 'in progress';
      $newHabit->fk_user = auth()->user()->id;
      $newHabit->save();
      $newPlanHabit = new Plan_habit();
      $newPlanHabit->fk_plan = $newPlan->id;
      $newPlanHabit->fk_habit = $newHabit->id;
      $newPlanHabit->save();
      $habitsArray[$planhabits->habits->id] = $newHabit->id;
    }
    //prizes
    foreach ($originalPlan->getPrizes as $prize) {
      $newPrize = new Prize;
      $newPrize->title = $prize->title;
      $newPrize->category = $prize->category;
      if ($prize->category == 'task') {
        $newPrize->fk_task = $tasksArray[$prize->fk_task];
      }
      if ($prize->category == 'goal') {
        $newPrize->fk_goal = $goalsArray[$prize->fk_goal];
      }
      if ($prize->category == 'habit') {
        $newPrize->fk_habit = $habitsArray[$prize->fk_habit];
      }
      $newPrize->fk_plan = $newPlan->id;
      $newPrize->save();
    }
    return Redirect::to('/planedit/' . $newPlan->id);
  }
  //TODO: add more questions maybe
  public function questionnaireFinished(Request $request)
  {
    $request->validate([
      'selectedAnswerValue' => 'required',
    ]);

    $plan = new Plan();
    $plan->fk_user = auth()->user()->id;
    $plan->title = 'Pavadinimas';
    $plan->color = 'white';
    $plan->active = 1;
    $plan->save();

    //first answer
    if ($request->selectedAnswerValue['_value'][0] <= 1) {
      $newTask = new Task();
      $newTask->title = 'Treniruotė';
      $newTask->duration = 30;
      $newTask->save();

      $newPlanTask = new Plan_task();
      $newPlanTask->execution_date = Carbon::now()->addDays(1);
      $newPlanTask->is_done = 0;
      $newPlanTask->fk_reminder = null;
      $newPlanTask->fk_task = $newTask->id;
      $newPlanTask->fk_plan = $plan->id;
      $newPlanTask->save();

      $existingGoal = Goal::where('title', 'Pagerinti fizinę formą')
        ->where('fk_user', auth()->user()->id)
        ->where('status', '!=', 'completed')
        ->first();
      // If the goal doesn't exist, create a new one
      if (!$existingGoal) {
        $newGoal = new Goal();
        $newGoal->title = 'Pagerinti fizinę formą';
        $newGoal->status = 'in progress';
        $newGoal->fk_user = auth()->user()->id;
        $newGoal->save();
      } else {
        $newGoal = $existingGoal;
        $newGoal->status = 'in progress';
        $newGoal->save();
      }

      $newPlanGoal = new Plan_goal();
      $newPlanGoal->fk_plan = $plan->id;
      $newPlanGoal->fk_goal = $newGoal->id;
      $newPlanGoal->save();

      $newHabit = new Habit();
      $newHabit->title = 'Pastovus sportavimas';
      $newHabit->status = 'in progress';
      $newHabit->fk_user = auth()->user()->id;
      $newHabit->save();

      $newPlanHabit = new Plan_habit();
      $newPlanHabit->fk_plan = $plan->id;
      $newPlanHabit->fk_habit = $newHabit->id;
      $newPlanHabit->save();

      $newPrize = new Prize();
      $newPrize->title = 'Proteino kokteilis';
      $newPrize->category = 'task';
      $newPrize->fk_task = $newTask->id;
      $newPrize->fk_plan = $plan->id;
      $newPrize->save();
    }
    //second answer
    if ($request->selectedAnswerValue['_value'][1] <= 1) {
      $newTask = new Task();
      $newTask->title = 'Išmokti naują dalyką';
      $newTask->duration = 30;
      $newTask->save();

      $newPlanTask = new Plan_task();
      $newPlanTask->execution_date = Carbon::now()->addDays(1);
      $newPlanTask->is_done = 0;
      $newPlanTask->fk_reminder = null;
      $newPlanTask->fk_task = $newTask->id;
      $newPlanTask->fk_plan = $plan->id;
      $newPlanTask->save();



      $existingGoal = Goal::where('title', 'Išmokti naujų dalykų')
        ->where('fk_user', auth()->user()->id)
        ->where('status', '!=', 'completed')
        ->first();
      // If the goal doesn't exist, create a new one
      if (!$existingGoal) {
        $newGoal = new Goal();
        $newGoal->title = 'Išmokti naujų dalykų';
        $newGoal->status = 'in progress';
        $newGoal->fk_user = auth()->user()->id;
        $newGoal->save();
      } else {
        $newGoal = $existingGoal;
        $newGoal->status = 'in progress';
        $newGoal->save();
      }
      $newPlanGoal = new Plan_goal();
      $newPlanGoal->fk_plan = $plan->id;
      $newPlanGoal->fk_goal = $newGoal->id;
      $newPlanGoal->save();

      $newHabit = new Habit();
      $newHabit->title = 'Pastovus mokymasis';
      $newHabit->status = 'in progress';
      $newHabit->fk_user = auth()->user()->id;
      $newHabit->save();

      $newPlanHabit = new Plan_habit();
      $newPlanHabit->fk_plan = $plan->id;
      $newPlanHabit->fk_habit = $newHabit->id;
      $newPlanHabit->save();

      // $newPrize = new Prize();
      // $newPrize->title = 'Antras atsakymas';
      // $newPrize->category = 'task';
      // $newPrize->fk_task = $newTask->id;
      // $newPrize->fk_plan = $plan->id;
      // $newPrize->save();
    }
    //third answer
    if ($request->selectedAnswerValue['_value'][2] <= 1) {
      $newTask = new Task();
      $newTask->title = 'Pabendrauti su žmonėmis';
      $newTask->duration = 30;
      $newTask->save();

      $newPlanTask = new Plan_task();
      $newPlanTask->execution_date = Carbon::now()->addDays(1);
      $newPlanTask->is_done = 0;
      $newPlanTask->fk_reminder = null;
      $newPlanTask->fk_task = $newTask->id;
      $newPlanTask->fk_plan = $plan->id;
      $newPlanTask->save();

      $existingGoal = Goal::where('title', 'Pagerinti socialinius santykius')
        ->where('fk_user', auth()->user()->id)
        ->where('status', '!=', 'completed')
        ->first();
      // If the goal doesn't exist, create a new one
      if (!$existingGoal) {
        $newGoal = new Goal();
        $newGoal->title = 'Pagerinti socialinius santykius';
        $newGoal->status = 'in progress';
        $newGoal->fk_user = auth()->user()->id;
        $newGoal->save();
      } else {
        $newGoal = $existingGoal;
        $newGoal->status = 'in progress';
        $newGoal->save();
      }

      $newPlanGoal = new Plan_goal();
      $newPlanGoal->fk_plan = $plan->id;
      $newPlanGoal->fk_goal = $newGoal->id;
      $newPlanGoal->save();

      $newHabit = new Habit();
      $newHabit->title = 'Pastovus bendravimas';
      $newHabit->status = 'in progress';
      $newHabit->fk_user = auth()->user()->id;
      $newHabit->save();

      $newPlanHabit = new Plan_habit();
      $newPlanHabit->fk_plan = $plan->id;
      $newPlanHabit->fk_habit = $newHabit->id;
      $newPlanHabit->save();
    }
    //fourth answer
    if ($request->selectedAnswerValue['_value'][3] <= 1) {
      $newTask = new Task();
      $newTask->title = 'Laikas sau';
      $newTask->duration = 30;
      $newTask->save();

      $newPlanTask = new Plan_task();
      $newPlanTask->execution_date = Carbon::now()->addDays(1);
      $newPlanTask->is_done = 0;
      $newPlanTask->fk_reminder = null;
      $newPlanTask->fk_task = $newTask->id;
      $newPlanTask->fk_plan = $plan->id;
      $newPlanTask->save();


      $existingGoal = Goal::where('title', 'Skirti laiko sau')
        ->where('fk_user', auth()->user()->id)
        ->where('status', '!=', 'completed')
        ->first();
      // If the goal doesn't exist, create a new one
      if (!$existingGoal) {
        $newGoal = new Goal();
        $newGoal->title = 'Skirti laiko sau';
        $newGoal->status = 'in progress';
        $newGoal->fk_user = auth()->user()->id;
        $newGoal->save();
      } else {
        $newGoal = $existingGoal;
        $newGoal->status = 'in progress';
        $newGoal->save();
      }

      $newPlanGoal = new Plan_goal();
      $newPlanGoal->fk_plan = $plan->id;
      $newPlanGoal->fk_goal = $newGoal->id;
      $newPlanGoal->save();

      $newHabit = new Habit();
      $newHabit->title = 'Pastovus laiko sau skyrimas';
      $newHabit->fk_user = auth()->user()->id;
      $newHabit->save();

      $newPlanHabit = new Plan_habit();
      $newPlanHabit->fk_plan = $plan->id;
      $newPlanHabit->fk_habit = $newHabit->id;
      $newPlanHabit->save();

      // $newPrize = new Prize();
      // $newPrize->title = 'Ketvirtas atsakymas';
      // $newPrize->category = 'task';
      // $newPrize->fk_task = $newTask->id;
      // $newPrize->fk_plan = $plan->id;
      // $newPrize->save();
    }
    //fifth answer
    if ($request->selectedAnswerValue['_value'][4] <= 1) {
      $newTask = new Task();
      $newTask->title = 'Sveikas patiekalas';
      $newTask->duration = 30;
      $newTask->save();

      $newPlanTask = new Plan_task();
      $newPlanTask->execution_date = Carbon::now()->addDays(1);
      $newPlanTask->is_done = 0;
      $newPlanTask->fk_reminder = null;
      $newPlanTask->fk_task = $newTask->id;
      $newPlanTask->fk_plan = $plan->id;
      $newPlanTask->save();

      $existingGoal = Goal::where('title', 'Sveikai maitintis')
        ->where('fk_user', auth()->user()->id)
        ->where('status', '!=', 'completed')
        ->first();
      // If the goal doesn't exist, create a new one
      if (!$existingGoal) {
        $newGoal = new Goal();
        $newGoal->title = 'Sveikai maitintis';
        $newGoal->status = 'in progress';
        $newGoal->fk_user = auth()->user()->id;
        $newGoal->save();
      } else {
        $newGoal = $existingGoal;
        $newGoal->status = 'in progress';
        $newGoal->save();
      }

      $newPlanGoal = new Plan_goal();
      $newPlanGoal->fk_plan = $plan->id;
      $newPlanGoal->fk_goal = $newGoal->id;
      $newPlanGoal->save();

      $newHabit = new Habit();
      $newHabit->title = 'Nuolatinė sveika mityba';
      $newHabit->status = 'in progress';
      $newHabit->fk_user = auth()->user()->id;
      $newHabit->save();

      $newPlanHabit = new Plan_habit();
      $newPlanHabit->fk_plan = $plan->id;
      $newPlanHabit->fk_habit = $newHabit->id;
      $newPlanHabit->save();
    }
    return Redirect::to('/planedit/' . $plan->id);
  }
}
