<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Achievement;
use App\Models\User_achievement;

class AchievementController extends Controller
{
    public function showAchievementsList()
    {
        $achievements = Achievement::orderBy('created_at','desc')->get();
        return Inertia::render('Achievements', ['achievements' => $achievements]);
    }
    public function addAchievement(Request $request)
    {
        $request -> validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'rewardXP' => 'required',
        ]);
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
        $request -> validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'rewardXP' => 'required',
        ]);
        $achievement = Achievement::find($request->id);
        $achievement->title = $request->title;
        $achievement->description = $request->description;
        $achievement->rewardXP = $request->rewardXP;
        $achievement->save();
    }
    public function showUserAchievements()
    {
        $achievements= User_achievement::where('fk_user',auth()->user()->id)->get()->load('getAchievement');
        return Inertia::render('Profile/Achievements', ['achievements' => $achievements]);
    }
}
