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

class ReflectionController extends Controller
{
  public function showReflection()
  {
    $questions = Reflection_question::orderBy('number')->get();
    $answers = Reflection_answer::all();
    $plans = Plan::where('fk_user', auth()->user()->id)->where('active', '=', 1)->whereNotIn('title', ['Iššūkis'])->with('getPlanHabits.habits')->get();
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
    //idea: make refleksija plan for each user and hide it everywhere and use it only to store reflection tasks
    $plan = Plan::where('fk_user', auth()->user()->id)->where('active', '=', 1)->whereNotIn('title', ['Iššūkis'])->first();
    $task = new Task();
    $task->duration = 15;
    $task->title = "Refleksija";
    $task->save();
    //adding reflection as a task
    $reflection = new Plan_task();
    $reflection->fk_plan = $plan->id;
    $reflection->fk_task = $task->id;
    $reflection->execution_date = Carbon::now('Europe/Vilnius');
    $reflection->is_done = 1;
    $reflection->save();
    $plans = Plan::where('fk_user', auth()->user()->id)->where('active', '=', 1)->whereNotIn('title', ['Iššūkis'])->with('getPlanHabits.habits')->get();
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
    $plans = Plan::where('fk_user', auth()->user()->id)->where('active', '=', 1)->whereNotIn('title', ['Iššūkis'])->with('getPlanHabits.habits')->get();
    $planCompleted = true;
    foreach ($plans as $plan) {
      foreach ($plan->getPlanHabits as $planHabit) {
        if ($planHabit->habits->status !== 'completed') {
          $planCompleted = false;
          break;
        }
      }
      if ($planCompleted) {
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
        error_log("Plan not completed");
      }
    }

    if ($planCompleted) {
      // The plan is completed
      // Perform the necessary actions
    } else {
      // The plan is not completed
      // Perform other actions
    }
    return Redirect::route('Schedule');
  }
}
