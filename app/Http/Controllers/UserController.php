<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Plan;
use App\Notifications\TaskReminder;
use App\Models\Challenged_user;
use App\Models\Note;
use Carbon\Carbon;

class UserController extends Controller
{
  public function showLeaderboard()
  {
    $top10 = User::where('is_admin', 0)->orderBy('xp', 'desc')->take(10)->get();
    $currentUserXP = auth()->user()->xp;
    $rank = User::where('is_admin', 0)->where('xp', '>', $currentUserXP)->count() + 1;
    $user = User::where('id', auth()->user()->id)->first();
    return Inertia::render('Leaderboard', ['top10' => $top10, 'rank' => $rank, 'user' => $user]);
  }
  public function showUsersList()
  {
    $users = User::where('is_admin', 0)->orderBy('created_at', 'desc')->get();
    return Inertia::render('Users', ['users' => $users]);
  }
  public function editUser(Request $request)
  {
    $request->validate([
      'username' => 'required',
      'email' => 'required',
      'xp' => 'required',
    ]);
    $user = User::find($request->id);
    $user->username = $request->username;
    $user->email = $request->email;
    $user->xp = $request->xp;
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
    $response = [
      'xp' => $user->xp,
      'level' => $user->level,
    ];
    return response()->json($response);
  }
  public function levelUp(Request $request)
  {
    $user = User::where('id', auth()->user()->id)->first();
    $user->level = $request->level;
    $user->save();
  }
  public function showDashboard()
  {
    $tasks = collect();
    $plan = Plan::where('fk_user', auth()->user()->id)->where('active', '=', 1)->get()->load('getTasks.getTask');
    foreach ($plan as $planItem) {
      $tasks = $tasks->merge($planItem->getTasks->load('getTask'));
    }
    $tasks = $tasks->where('is_done', 0);
    $tasks = $tasks->where('execution_date', '>', Carbon::now());
    $tasks = $tasks->sortBy('execution_date');
    $allTasksArray = array_values($tasks->toArray());
    $taskArray = array_slice($allTasksArray, 0, 3);

    $notes = Note::where('fk_user', auth()->user()->id)->orderBy('created_at', 'desc')->take(3)->get();

    $notification = Challenged_user::where('fk_user', auth()->user()->id)
      ->where('status', 'pending')
      ->whereHas('challenge', function ($query) {
        $query->where('type', '<>', 'public');
      })
      ->with(['challenge', 'challenge.challenge_author'])
      ->get();
    return Inertia::render('Dashboard', ['tasks' => $taskArray, 'notes' => $notes, 'notification' => $notification]);
  }
}
