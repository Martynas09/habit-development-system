<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlanController;
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
    Route::get('/plans/view{id}', [PlanController::class, 'showPlan'])->name('Plan.PlanView');
    Route::post('/plans/view{id}', [PlanController::class, 'taskDone'])->name('Plan.PlanView');
});


require __DIR__ . '/auth.php';
