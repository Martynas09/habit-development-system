<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Plan;

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
}
