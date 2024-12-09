<?php

use App\Http\Controllers\ProfileController;
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
    ]);
})->middleware(['auth'])->name('indicator.form');

// rota para logout do sistema
Route::get('/actionplanform', function () {
    return Inertia::render('ActionPlanForm');
})->name('actionplan.form');

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
});

require __DIR__.'/auth.php';
