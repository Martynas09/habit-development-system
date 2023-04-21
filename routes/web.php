<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReflectionController;
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

Route::get('/getUserXp', [UserController::class, 'getUserXp']);
//PROFILE ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('profile/characterEdit', [CharacterController::class, 'characterShow'])->name('profile.characterEdit');
    Route::post('profile/characterEdit', [CharacterController::class, 'characterEdit'])->name('profile.characterEdit');
    Route::get('profile/achievements', [AchievementController::class, 'showUserAchievements'])->name('profile.achievements');
});

//PLAN ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/plans', [PlanController::class, 'showPlans'])->name('Plan.PlanListView');
    Route::get('/plans/alternatives', [PlanController::class, 'showAlternatives'])->name('Plan.ChooseAlternativeView');
    Route::get('/plans/questionnaire', [PlanController::class, 'showQuestionnaire'])->name('Plan.QuestionnaireView');
    Route::post('/plans/questionnaire', [PlanController::class, 'questionnaireFinished']);
    Route::get('/plans/custom', [PlanController::class, 'showCustom'])->name('Plan.CustomView');
    Route::post('/plans/custom', [PlanController::class, 'createPlan'])->name('Plan.CustomView');
    Route::get('/planedit/{id}', [PlanController::class, 'showPlanEdit'])->name('Plan.PlanEditView');
    Route::post('/planedit/{id}', [PlanController::class, 'editPlan'])->name('Plan.PlanEditView');
    Route::post('/planDelete', [PlanController::class, 'deletePlan']);
    Route::get('/plans/recommended', [PlanController::class, 'showRecommended'])->name('Plan.RecommendedView');
    Route::post('/plans/recommended', [PlanController::class, 'selectRecommended']);

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
    Route::get('/api/isPrize', [ScheduleController::class, 'isPrize']);
});

//LEADERBOARD ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/leaderboard', [UserController::class, 'showLeaderboard'])->name('Leaderboard');
});

//GOALS ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/goals', [GoalController::class, 'showGoalsList'])->name('MyGoals');
    Route::post('/goalEdit', [GoalController::class, 'editGoal']);
    Route::post('/goalDelete', [GoalController::class, 'deleteGoal']);
    Route::post('/goalAdd', [GoalController::class, 'addGoal']);
});

//JOURNAL ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/journal', [JournalController::class, 'showJournal'])->name('Journal');
    Route::post('/journal', [JournalController::class, 'addNote'])->name('Journal');
    Route::post('/noteDelete', [JournalController::class, 'deleteNote']);
    Route::post('/noteEdit', [JournalController::class, 'editNote']);
});
//REPORT ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/report', [ReportController::class, 'showReport'])->name('Report');
});
//REFLECTION ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/reflection', [ReflectionController::class, 'showReflection'])->name('Reflection');
    Route::post('/reflection', [ReflectionController::class, 'reflectionFinished'])->name('Reflection');
});

//ADMIN ROUTES

//ACHIEVEMENTS ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/achievements', [AchievementController::class, 'showAchievementsList'])->name('Achievements');
    Route::post('/achievementEdit', [AchievementController::class, 'editAchievement']);
    Route::post('/achievementDelete', [AchievementController::class, 'deleteAchievement']);
    Route::post('/achievementAdd', [AchievementController::class, 'addAchievement']);
});
//USERS ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/users', [UserController::class, 'showUsersList'])->name('Users');
    Route::post('/userEdit', [UserController::class, 'editUser']);
    Route::post('/userDelete', [UserController::class, 'deleteUser']);
    Route::post('/userAdd', [UserController::class, 'addUser']);
});
//CHARACTER ITEMS ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/characterItems', [CharacterController::class, 'showCharacterItemsList'])->name('CharacterItems');
    Route::post('/characterItemEdit', [CharacterController::class, 'editCharacterItem']);
    Route::post('/characterItemDelete', [CharacterController::class, 'deleteCharacterItem']);
    Route::post('/characterItemAdd', [CharacterController::class, 'addCharacterItem']);
    Route::post('/api/addImage', [CharacterController::class, 'pictureUpload'])->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);;
});
//REFLECTION QUESTIONS ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/reflectionQuestions', [QuestionController::class, 'showReflectionQuestionsList'])->name('Questions');
    Route::post('/reflectionQuestionEdit', [QuestionController::class, 'editReflectionQuestion']);
    Route::post('/reflectionQuestionDelete', [QuestionController::class, 'deleteReflectionQuestion']);
    Route::post('/reflectionQuestionAdd', [QuestionController::class, 'addReflectionQuestion']);
    Route::post('/reflectionAnswerEdit', [QuestionController::class, 'editReflectionAnswer']);
    Route::post('/reflectionAnswerDelete', [QuestionController::class, 'deleteReflectionAnswer']);
});


require __DIR__ . '/auth.php';
