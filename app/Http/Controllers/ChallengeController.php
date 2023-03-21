<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Challenge;
use App\Models\Challenged_user;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

class ChallengeController extends Controller
{
    public function showChallengesList()
    {
        $publicChallenges = Challenge::where('type', 'public')->get();
        $authorPrivateChallenges = Challenge::where('fk_user', auth()->user()->id)->where('type', 'private')->get()->load('challenged_users','challenged_users.user');
        $receivedChallenges=Challenged_user::where('fk_user', auth()->user()->id)->get()->load('challenge','challenge.challenge_author');

        return inertia::render('Challenge/ChallengesListView', [
            'publicChallenges' => $publicChallenges,
            'authorPrivateChallenges' => $authorPrivateChallenges,
            'receivedChallenges' => $receivedChallenges,
        ]);
    }
    public function showChallengeSend()
    {
        $usersTemp = User::where('id', '!=', auth()->user()->id)->get();
        //make users object with only id and name
        $users = [];
        foreach ($usersTemp as $user) {
            $users[] = [
                'id' => $user->id,
                'value' => $user->username,
                'label' => $user->username,
            ];
        }
        return inertia::render('Challenge/ChallengeSendView', [
            'users' => $users,
        ]);
    }

    public function challengeSend(Request $request){
        $challenge=new Challenge();
        $challenge->title=$request->title;
        $challenge->description=$request->description;
        $challenge->type='private';
        $challenge->xpGiven=$request->xpGiven;
        $challenge->duration=$request->duration;
        $challenge->timesPerWeek=$request->timesPerWeek;
        $challenge->fk_user=auth()->user()->id;
        $challenge->save();

        foreach($request->receivers as $receiver){
            $challenged_user=new Challenged_user();
            $challenged_user->fk_challenge=$challenge->id;
            $challenged_user->fk_user=$receiver;
            $challenged_user->status='pending';
            $challenged_user->save();
        }
        return Redirect::route('Challenge.ChallengesListView');
    }
}
