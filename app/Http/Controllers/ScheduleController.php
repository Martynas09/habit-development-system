<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Plan;
use App\Models\Plan_task;
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
    public function taskDone(Request $request)
    {
        $task = Plan_task::where('id', $request->id)->first();
        $task->is_done = 1;
        $task->save();
    }
}
