<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\ScheduleController;
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
});

//CHALLENGE ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/challenges', [ChallengeController::class, 'showChallengesList'])->name('Challenge.ChallengesListView');
});

//SCHEDULE ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/schedule', [ScheduleController::class, 'showSchedule'])->name('Schedule');
});


require __DIR__ . '/auth.php';
