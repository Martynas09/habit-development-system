<?php

namespace App\Http\Controllers;

use App\Models\Plan_task;
use App\Models\Plan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function showReport()
    {
        //$tasks = Plan_task::all();
        $tasks = collect();

        $plan = Plan::where('fk_user', auth()->user()->id)->get()->load('getTasks.getTask');
        foreach ($plan as $planItem) {
            $tasks = $tasks->merge($planItem->getTasks->load('getTask'));
        }
        $tasks = $tasks->where('is_done', 1);

        // Initialize an array to hold the task counts for each month
        $taskCounts = array_fill(0, 12, 0);

        // Loop through each task and increment the corresponding month's count
        foreach ($tasks as $task) {
            $month = date('n', strtotime($task->execution_date));
            $taskCounts[$month - 1]++;
        }
        return inertia::render('Report', ['completedTasks' => $taskCounts]);
    }
}
