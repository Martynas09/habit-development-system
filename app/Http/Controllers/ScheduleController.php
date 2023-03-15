<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Plan;

class ScheduleController extends Controller
{
    public function showSchedule()
    {
        $plan=Plan::where('fk_user', auth()->user()->id)->where('active', '=', 1)->get()->load('getTasks.getTask');
        return inertia::render('Schedule', [
            'plan' => $plan
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
