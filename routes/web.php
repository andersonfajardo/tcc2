<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IndicatorController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// render para a pagina de dash do projeto anterior.
//Route::get('/dashboardform', function () {
//    return Inertia::render('DashboardForm');
//})->name('dashboard.form');

// rota para o dashboard form ser exibido somente para o usuário logado

Route::get('/dashboardform', function () {
    $user = Auth::user()->load('company'); // Inclui a empresa associada
    return Inertia::render('DashboardForm', [
        //'user' => auth()->user()
        'user' => $user,
        'company' => $user->company, // Adiciona a empresa ao Inertia
    ]);
})->middleware(['auth'])->name('dashboard.form');

Route::get('/indicatorform', function () {
    $user = Auth::user()->load('company');
    return Inertia::render('IndicatorForm', [
        'user' => $user,
        'company' => $user->company,
        'indicatorsload' => $user->getUserIndicators
    ]);
})->middleware(['auth'])->name('indicator.form');

Route::get('/actionplanform', function () {
    $user = Auth::user()->load('company'); // Inclui a empresa associada
    return Inertia::render('ActionPlanForm', [
        //'user' => auth()->user()
        'user' => $user,
        'company' => $user->company,
        'actionplanload' => $user->getUserActionPlan()
    ]);
})->middleware(['auth'])->name('actionplan.form');

Route::get('/dataform', function () {
    return Inertia::render('DataForm');
})->name('dataform.form');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/indicators', [IndicatorController::class, 'index'])->name('indicators.index');
    Route::post('/indicators', [IndicatorController::class, 'store'])->name('indicators.store');
    Route::patch('/indicators', [IndicatorController::class, 'patch'])->name('indicators.patch');
    Route::patch('/indicators/enable', [IndicatorController::class, 'patchEnable'])->name('indicators.patchEnable');
    Route::put('/indicators/{id}', [IndicatorController::class, 'update'])->name('indicators.update');
    Route::delete('/indicators/{id}', [IndicatorController::class, 'destroy'])->name('indicators.destroy');
});

require __DIR__.'/auth.php';
