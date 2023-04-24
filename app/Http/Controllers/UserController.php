<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use App\Notifications\TaskReminder;
use Carbon\Carbon;

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
        $request -> validate([
            'username' => 'required',
            'email' => 'required',
            'xp' => 'required',
        ]);
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
    public function notification(){
        $reminders = Reminder::where('remind_time', '<=', Carbon::now('Europe/Vilnius')->addMinutes(15))
        ->where('remind_time', '>', Carbon::now('Europe/Vilnius'))
        ->where('is_sent', 0)
        ->get();
        foreach($reminders as $reminder){
            $minutes = Carbon::now('Europe/Vilnius')->diffInMinutes($reminder->remind_time);
            $title=$reminder->planTask->getTask->title;
            $user = User::where('id', $reminder->planTask->getPlan->fk_user)->first();
            $user->notify(new TaskReminder($title,$minutes));
            $reminder->is_sent = 1;
            $reminder->save();
        }
    }
}
