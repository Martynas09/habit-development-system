<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;

class UserController extends Controller
{
    public function showLeaderboard()
    {
        $top10 = User::where('is_admin', 0)->orderBy('xp', 'desc')->take(10)->get();
        $currentUserXP = auth()->user()->xp;
        $rank = User::where('is_admin', 0)->where('xp', '>', $currentUserXP)->count() + 1;
        $user=User::where('id', auth()->user()->id)->first();
        return Inertia::render('Leaderboard', ['top10' => $top10, 'rank' => $rank, 'user' => $user]);
    }
    public function showUsersList()
    {
        $users = User::where('is_admin', 0)->orderBy('created_at','desc')->get();
        return Inertia::render('Users', ['users' => $users]);
    }
    public function editUser(Request $request)
    {
        $user = User::find($request->id);
        $user->username = $request->username;
        $user->email = $request->email;
        $user->xp=$request->xp;
        $user->save();
    }
    public function deleteUser(Request $request)
    {
        $user = User::find($request->id);
        $user->delete();
    }
    public function getUserXp()
    {
        $user = User::where('id', auth()->user()->id)->first();
        return response()->json($user->xp);
    }
}
