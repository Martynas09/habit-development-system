<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;

class UserController extends Controller
{
    public function leaderboardView()
    {
        $top10 = User::orderBy('xp', 'desc')->take(10)->get();
        $currentUserXP = auth()->user()->xp;
        $rank = User::where('xp', '>', $currentUserXP)->count() + 1;
        $user=User::where('id', auth()->user()->id)->first();
        return Inertia::render('Leaderboard', ['top10' => $top10, 'rank' => $rank, 'user' => $user]);
    }
}
