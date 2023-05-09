<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Plan;
use App\Models\Plan_task;
use App\Models\User;
use App\Models\Challenged_user;
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
            if($lastTask!=null){
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
    //TODO: fix finishing iššūkis
    public function taskDone(Request $request)
    {
        $user = User::where('id', auth()->user()->id)->first();
        $user->xp += 5;
        $user->save();
        $task = Plan_task::where('id', $request->id)->first();
        $task->is_done = 1;
        $task->save();
        if(strpos($task->getPlan->title, "Iššūkis") !== false){
          $status=1;
          foreach($task->getPlan->getTasks as $challengeTask){
            if($challengeTask->is_done==0){
              $status=0;
              break;
            }
          }
          if($status==1){
            $challengeId = preg_replace('/[^0-9]/', '', $challengeTask->getPlan->title);
            $challengeTask->getPlan->active=0;
            $challengeTask->getPlan->save();
            $challengeToComplete = Challenged_user::where('fk_user', auth()->user()->id)->where('fk_challenge', $challengeId)->first();
            $challengeToComplete->status = 'completed';
          }
        }
    }
    public function isPrize(Request $request){
        $task = Plan_task::where('id', $request->id)->first()->load('getTask');
        $plan=Plan::where('id',$task->fk_plan)->first()->load('getPrizes');
        foreach($plan->getPrizes as $prize){
            if($prize->fk_task==$task->getTask->id){
                return response()->json($prize);
                break;
            }
        }
    }
}
