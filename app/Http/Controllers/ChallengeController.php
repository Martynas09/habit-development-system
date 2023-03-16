<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Challenge;
use App\Models\Challenged_user;

class ChallengeController extends Controller
{
    public function showChallengesList()
    {
        $publicChallenges = Challenge::where('type', 'public')->get();
        $authorPrivateChallenges = Challenge::where('fk_user', auth()->user()->id)->where('type', 'private')->get();
        $receivedChallenges=Challenged_user::where('fk_user', auth()->user()->id)->get()->load('challenge','challenge.challenge_author');

        return inertia::render('Challenge/ChallengesListView', [
            'publicChallenges' => $publicChallenges,
            'authorPrivateChallenges' => $authorPrivateChallenges,
            'receivedChallenges' => $receivedChallenges,
        ]);
        
    }
}
