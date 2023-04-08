<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Achievement;

class AchievementController extends Controller
{
    public function showAchievementsList()
    {
        $achievements = Achievement::orderBy('created_at','desc')->get();
        return Inertia::render('Achievements', ['achievements' => $achievements]);
    }
    public function addAchievement(Request $request)
    {
        $achievement = new Achievement();
        $achievement->title = $request->title;
        $achievement->description = $request->description;
        $achievement->rewardXP = $request->rewardXP;
        $achievement->save();
    }
    public function deleteAchievement(Request $request)
    {
        $achievement = Achievement::find($request->id);
        $achievement->delete();
    }
    public function editAchievement(Request $request)
    {
        $achievement = Achievement::find($request->id);
        $achievement->title = $request->title;
        $achievement->description = $request->description;
        $achievement->rewardXP = $request->rewardXP;
        $achievement->save();
    }
}
