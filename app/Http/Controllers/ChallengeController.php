<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Challenge;
use App\Models\Challenged_user;
use App\Models\User;
use App\Models\Plan;
use App\Models\Task;
use App\Models\Plan_task;
use App\Models\Reminder;
use DateTimeImmutable;
use DateInterval;
use Illuminate\Support\Facades\Redirect;

class ChallengeController extends Controller
{
  public function showChallengesList()
  {
    $publicChallenges = Challenge::where('type', 'public')
      ->with(['challenged_users' => function ($query) {
        $query->where('fk_user', auth()->user()->id);
      }])
      ->get();
    $authorPrivateChallenges = Challenge::where('fk_user', auth()->user()->id)->where('type', 'private')->get()->load('challenged_users', 'challenged_users.user');
    $receivedChallenges = Challenged_user::where('fk_user', auth()->user()->id)
      ->whereHas('challenge', function ($query) {
        $query->where('type', '<>', 'public');
      })
      ->with(['challenge', 'challenge.challenge_author'])
      ->get();

    return inertia::render('Challenge/ChallengesListView', [
      'publicChallenges' => $publicChallenges,
      'authorPrivateChallenges' => $authorPrivateChallenges,
      'receivedChallenges' => $receivedChallenges,
    ]);
  }
  public function showChallengeSend()
  {
    $usersTemp = User::where('id', '!=', auth()->user()->id)
      ->where('is_admin', '!=', 1)
      ->get();
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

  public function challengeSend(Request $request)
  {
    $request->validate([
      'title' => 'required',
      'description' => 'required',
      'xpGiven' => 'required',
      'duration' => 'required',
      'timesPerWeek' => 'required',
      'receivers' => 'required',
    ]);
    $challenge = new Challenge();
    $challenge->title = $request->title;
    $challenge->description = $request->description;
    $challenge->type = 'private';
    $challenge->xpGiven = $request->xpGiven;
    $challenge->duration = $request->duration;
    $challenge->timesPerWeek = $request->timesPerWeek;
    $challenge->fk_user = auth()->user()->id;
    $challenge->save();

    foreach ($request->receivers as $receiver) {
      $challenged_user = new Challenged_user();
      $challenged_user->fk_challenge = $challenge->id;
      $challenged_user->fk_user = $receiver;
      $challenged_user->status = 'pending';
      $challenged_user->save();
    }
    return Redirect::route('Challenge.ChallengesListView');
  }
  public function challengeAcceptView($id)
  {
    $challenge = Challenge::where('id', $id)->first();
    return inertia::render('Challenge/ChallengeAcceptView', [
      'challenge' => $challenge,
    ]);
  }
  public function challengeAccept($id, Request $request)
  {
    $request->validate([
      'title' => 'required',
      'duration' => 'required',
      'days' => 'required',
    ]);
    $challenge = Challenge::where('id', $id)->first();
    if ($challenge->type == 'private') {
      $challenged_user = Challenged_user::where('fk_challenge', $id)->where('fk_user', auth()->user()->id)->first();
      $challenged_user->status = 'accepted';
      $challenged_user->save();
    } else {
      $challenged_user = new Challenged_user();
      $challenged_user->fk_challenge = $id;
      $challenged_user->fk_user = auth()->user()->id;
      $challenged_user->status = 'accepted';
      $challenged_user->save();
    }

    $plan = new Plan();
    $plan->fk_user = auth()->user()->id;
    $plan->title = "Iššūkis" . $id;
    $plan->color = "#CA33FF";
    $plan->active = 1;
    $plan->save();

    $newTask = new Task();
    $newTask->title = $request->title;
    $newTask->duration = $request->duration;
    $newTask->save();

    foreach ($request->days as $day) {
      $taskTime = $request->time;
      $dateTime = DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s.u\Z', $taskTime)
        ->add(new DateInterval('PT3H'));
      $hoursAndMinutes = $dateTime->format('H:i');
      for ($i = 0; $i < 1; $i++) {
        $upcomingDay = new DateTimeImmutable('next ' . ucfirst($day) . ' +' . $i . ' week');
        $tempDate = $upcomingDay->format('Y-m-d') . ' ' . $hoursAndMinutes;
        if ($request->reminder == 'system') {
          $newReminder = new Reminder();
          $newReminder->remind_time = DateTimeImmutable::createFromFormat('Y-m-d H:i', $tempDate);
          $newReminder->save();
        }
        $newPlanTask = new Plan_task();
        $newPlanTask->execution_date = DateTimeImmutable::createFromFormat('Y-m-d H:i', $tempDate);
        $newPlanTask->is_done = 0;
        if ($request->reminder == 'system') {
          $newPlanTask->fk_reminder = $newReminder->id;
        } else {
          $newPlanTask->fk_reminder = null;
        }
        $newPlanTask->fk_task = $newTask->id;
        $newPlanTask->fk_plan = $plan->id;
        $newPlanTask->save();
      }
    }
    return Redirect::route('Challenge.ChallengesListView');
  }
  //TODO
  public function ChallengeDecline(Request $request)
  {
    $challenged_user = Challenged_user::where('fk_challenge', $request->id)->where('fk_user', auth()->user()->id)->first();
    $challenged_user->status = 'rejected';
    $challenged_user->save();
  }
}
