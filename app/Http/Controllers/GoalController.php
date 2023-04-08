<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Goal;


class GoalController extends Controller
{
    public function showGoalsList()
    {
        // $goals = Goal::whereHas('plan_goal.plan', function ($query) {
        //     $query->where('fk_user', auth()->user()->id);
        // })->with('plan_goal.plan')->get();
        $goals=Goal::where('fk_user', auth()->user()->id)->orderBy('created_at','desc')->paginate(99)->load('plan_goal.plan');
        return Inertia::render('MyGoals', ['goals' => $goals]);
    }
    public function addGoal(Request $request)
    {
        $goal = new Goal();
        $goal->title = $request->title;
        $goal->status='not in progress';
        $goal->fk_user = auth()->user()->id;
        $goal->save();
    }
    public function editGoal(Request $request)
    {
        $goal = Goal::find($request->id);
        $goal->title = $request->title;
        $goal->save();
    }
    public function deleteGoal(Request $request)
    {
        $goal = Goal::find($request->id);
        $goal->delete();
    }
}
