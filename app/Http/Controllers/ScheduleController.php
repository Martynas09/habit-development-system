<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Plan;

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
        return inertia::render('Schedule', [
            'plan' => $plan,
            'tasks' => $allTasksArray
        ]);
    }
    //TODO TASK DONE
    public function taskDone(Request $request)
    {
        // $task = Plan_task::where('id', $request->id)->first();
        // $task->is_done = 1;
        // $task->save();
    }
}
