<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Goal;


class GoalController extends Controller
{
    public function showGoalsList()
    {
        $goals = Goal::whereHas('plan_goal.plan', function ($query) {
            $query->where('fk_user', auth()->user()->id);
        })->with('plan_goal.plan')->get();
        return Inertia::render('MyGoals', ['goals' => $goals]);
    }
}
