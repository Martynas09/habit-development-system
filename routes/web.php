<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\JournalController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//PROFILE ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('profile/characterEdit', [ProfileController::class, 'characterShow'])->name('profile.characterEdit');
    Route::post('profile/characterEdit', [ProfileController::class, 'characterEdit'])->name('profile.characterEdit');
});

//PLAN ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/plans', [PlanController::class, 'showPlans'])->name('Plan.PlanListView');
    Route::get('/plans/alternatives', [PlanController::class, 'showAlternatives'])->name('Plan.ChooseAlternativeView');
    Route::get('/plans/questionnaire', [PlanController::class, 'showQuestionnaire'])->name('Plan.QuestionnaireView');
    Route::get('/plans/custom', [PlanController::class, 'showCustom'])->name('Plan.CustomView');
    Route::post('/plans/custom', [PlanController::class, 'createPlan'])->name('Plan.CustomView');
    Route::get('/planedit/{id}', [PlanController::class, 'showPlanEdit'])->name('Plan.PlanEditView');
    Route::post('/planedit/{id}', [PlanController::class, 'editPlan'])->name('Plan.PlanEditView');
    Route::post('/planDelete/{id}', [PlanController::class, 'deletePlan']);
});

//CHALLENGE ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/challenges', [ChallengeController::class, 'showChallengesList'])->name('Challenge.ChallengesListView');
    Route::get('/challenges/send', [ChallengeController::class, 'showChallengeSend'])->name('Challenge.ChallengeSendView');
    Route::post('/challenges/send', [ChallengeController::class, 'challengeSend'])->name('Challenge.ChallengeSendView');
    Route::get('/challenge/accept/{id}', [ChallengeController::class, 'challengeAcceptView'])->name('Challenge.ChallengeAcceptView');
    Route::post('/challenge/accept/{id}', [ChallengeController::class, 'challengeAccept'])->name('Challenge.ChallengeAcceptView');
});

//SCHEDULE ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/schedule', [ScheduleController::class, 'showSchedule'])->name('Schedule');
    Route::post('/schedule', [ScheduleController::class, 'taskDone'])->name('Schedule');
});

//LEADERBOARD ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/leaderboard', [UserController::class, 'showLeaderboard'])->name('Leaderboard');
});

//GOALS ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/goals', [GoalController::class, 'showGoalsList'])->name('MyGoals');
});

//JOURNAL ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/journal', [JournalController::class, 'showJournal'])->name('Journal');
    Route::post('/journal', [JournalController::class, 'addNote'])->name('Journal');
});


require __DIR__ . '/auth.php';
