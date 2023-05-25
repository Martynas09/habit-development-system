<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Plan;
use App\Models\Plan_task;
use App\Models\User;
use App\Models\Challenged_user;
use App\Models\User_achievement;
use Carbon\Carbon;

class ScheduleController extends Controller
{
  public function showSchedule()
  {
    $tasks = collect();

    $plan = Plan::where('fk_user', auth()->user()->id)->where('active', '=', 1)->get()->load('getTasks.getTask');
    foreach ($plan as $planItem) {
      $tasks = $tasks->merge($planItem->getTasks->load('getTask'));
    }
    $tasks = $tasks->sortBy('execution_date');
    $allTasksArray = array_values($tasks->toArray());

    //REFLECTION
    $oneWeekPassed = false;

    $lastReflection = $tasks->first(function ($task) {
      return $task->getTask->title === 'Refleksija';
    });
    if ($lastReflection != null) {
      $executionDate = Carbon::parse($lastReflection->execution_date);
      $oneWeekAgo = now()->subWeek();
      if ($executionDate->lessThan($oneWeekAgo)) {
        // One week has passed since the last reflection
        $oneWeekPassed = true;
      }
    } else {
      //last done task
      $lastTask = $tasks->where('is_done', 1)->first();
      if ($lastTask != null) {
        $executionDate = Carbon::parse($lastTask->execution_date);
        $oneWeekAgo = now()->subWeek();
        if ($executionDate->lessThan($oneWeekAgo)) {
          // One week has passed since the last task
          $oneWeekPassed = true;
        }
      }
    }
    return inertia::render('Schedule', [
      'plan' => $plan,
      'tasks' => $allTasksArray,
      'oneWeekPassed' => $oneWeekPassed,
    ]);
  }
  public function taskDone(Request $request)
  {
    $user = User::where('id', auth()->user()->id)->first();
    $user->xp += 5;
    $user->save();
    $task = Plan_task::where('id', $request->id)->first();
    $task->is_done = 1;
    $task->save();

    //check if challenge is completed
    if (strpos($task->getPlan->title, "Iššūkis") !== false) {
      $status = 1;
      foreach ($task->getPlan->getTasks as $challengeTask) {
        if ($challengeTask->is_done == 0) {
          $status = 0;
          break;
        }
      }
      if ($status == 1) {
        $challengeId = preg_replace('/[^0-9]/', '', $challengeTask->getPlan->title);
        error_log($challengeId);
        $challengeTask->getPlan->active = 2;
        $challengeTask->getPlan->save();
        $challengeToComplete = Challenged_user::where('fk_user', auth()->user()->id)->where('fk_challenge', $challengeId)->first();
        $challengeToComplete->status = 'completed';
        $challengeToComplete->save();
      }
    }
  }
  public function isPrize(Request $request)
  {
    $task = Plan_task::where('id', $request->id)->first()->load('getTask');
    $plan = Plan::where('id', $task->fk_plan)->first()->load('getPrizes');
    foreach ($plan->getPrizes as $prize) {
      if ($prize->fk_task == $task->getTask->id) {
        return response()->json($prize);
        break;
      }
    }
  }
  public function isAchievement()
  {
    $tasks = collect();

    $plan = Plan::where('fk_user', auth()->user()->id)->where('title', '!=', 'Refleksija')->get()->load('getTasks.getTask');
    foreach ($plan as $planItem) {
      $tasks = $tasks->merge($planItem->getTasks->load('getTask'));
    }
    $tasks = $tasks->where('is_done', 1);
    $count = $tasks->count();

    if ($count == 1) {
      $text = 'Pirma atlikta užduotis';
      $achievement = Achievement::where('title', $text)->first();
      $userAchievemet = new User_achievement();
      $userAchievemet->fk_user = auth()->user()->id;
      $userAchievemet->fk_achievement = $achievement->id;
      $userAchievemet->save();
      $user = User::where('id', auth()->user()->id)->first();
      $user->xp += $achievement->rewardXP;
      $user->save();
    } else if ($count == 20) {
      $text = '20 atliktų užduočių';
      $achievement = Achievement::where('title', $text)->first();
      $userAchievemet = new User_achievement();
      $userAchievemet->fk_user = auth()->user()->id;
      $userAchievemet->fk_achievement = $achievement->id;
      $userAchievemet->save();
      $user = User::where('id', auth()->user()->id)->first();
      $user->xp += $achievement->rewardXP;
      $user->save();
    } else if ($count == 50) {
      $text = '50 atliktų užduočių';
      $achievement = Achievement::where('title', $text)->first();
      $userAchievemet = new User_achievement();
      $userAchievemet->fk_user = auth()->user()->id;
      $userAchievemet->fk_achievement = $achievement->id;
      $userAchievemet->save();
      $user = User::where('id', auth()->user()->id)->first();
      $user->xp += $achievement->rewardXP;
      $user->save();
    }
    return response()->json($text);
  }
}
