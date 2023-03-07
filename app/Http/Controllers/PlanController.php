<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Plan;
use App\Models\Plan_task;

class PlanController extends Controller
{
    public function showPlans()
    {
        $plans = Plan::where('fk_user', auth()->user()->id)->get();
        return inertia::render('Plan/PlanListView', [
            'plans' => $plans
        ]);
    }
    public function showPlan($id)
    {
        $plan = Plan::where('id', $id)->first()->load('getTasks.getTask');
        return inertia::render('Plan/PlanView', [
            'plan' => $plan
        ]);
    }
    public function taskDone(Request $request)
    {
        $task = Plan_task::where('id', $request->id)->first();
        $task->is_done = 1;
        $task->save();
    }
    public function showAlternatives()
    {
        return inertia::render('Plan/ChooseAlternativeView');
    }
    public function showQuestionnaire()
    {
        return inertia::render('Plan/QuestionnaireView');
    }
}
