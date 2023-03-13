<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Challenge;

class ChallengeController extends Controller
{
    public function showChallengesList()
    {
        $challenges = Challenge::all();
        return inertia::render('Challenge/ChallengesListView', [
            'challenges' => $challenges
        ]);
        
    }
}
