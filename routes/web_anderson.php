<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndicatorController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\ActionPlanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ReportController;


// Tela Inicial do Backend
Route::get('/', function () {
    return response()->json([
        'messages' => [
            'Backend do sistema OKR/KPI',
            'TCC 2',
            'Desenvolvido por: Anderson e Luis',
        ]
    ]);
});

// Rota de Login
Route::get('login', function () {
    return response()->json([
        'message' => 'Página de login',
        'csrf_token' => csrf_token()
    ]);
})->name('login');

//token temporário

Route::get('/csrf-token', function () {
    return response()->json([
        'csrf_token' => csrf_token(),
    ]);
});

// gerar logs

Route::get('/test-log', function () {
    Log::info('Test log message.');
    return 'Log test completed.';
});

// Rotas de autenticação
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('password/recovery', [AuthController::class, 'recoverPassword'])->name('auth.recoverPassword');
    Route::put('password/update', [AuthController::class, 'updatePassword'])->name('auth.updatePassword');
});

// Rotas protegidas por autenticação
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::put('/layout', [DashboardController::class, 'updateLayout'])->name('dashboard.updateLayout');
    });

    // Indicadores (OKRs e KPIs)
    Route::prefix('indicators')->group(function () {
        Route::get('/', [IndicatorController::class, 'index'])->name('indicators.index');
        Route::post('/', [IndicatorController::class, 'store'])->name('indicators.store');
        Route::put('/{id}', [IndicatorController::class, 'update'])->name('indicators.update');
        Route::delete('/{id}', [IndicatorController::class, 'destroy'])->name('indicators.destroy');
        Route::post('/validate', [IndicatorController::class, 'validateData'])->name('indicators.validate');
        Route::get('/{id}/history', [IndicatorController::class, 'getHistory'])->name('indicators.history');
    });

    // Dados de Indicadores
    Route::prefix('data')->group(function () {
        Route::post('/', [DataController::class, 'store'])->name('data.store');
        Route::post('/upload', [DataController::class, 'upload'])->name('data.upload');
    });

    // Planos de Ação
    Route::prefix('action-plans')->group(function () {
        Route::get('/', [ActionPlanController::class, 'index'])->name('actionPlans.index');
        Route::post('/', [ActionPlanController::class, 'store'])->name('actionPlans.store');
        Route::put('/{id}', [ActionPlanController::class, 'update'])->name('actionPlans.update');
        Route::delete('/{id}', [ActionPlanController::class, 'destroy'])->name('actionPlans.destroy');
    });

    // Notificações
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/settings', [NotificationController::class, 'updateSettings'])->name('notifications.settings');
    });

    // Integrações
    Route::prefix('integrations')->group(function () {
        Route::get('/api/open', [IntegrationController::class, 'getOpenApi'])->name('integrations.openApi');
        Route::post('/', [IntegrationController::class, 'createIntegration'])->name('integrations.create');
    });

    // Templates
    Route::prefix('templates')->group(function () {
        Route::get('/', [TemplateController::class, 'index'])->name('templates.index');
        Route::post('/', [TemplateController::class, 'store'])->name('templates.store');
        Route::put('/{id}', [TemplateController::class, 'update'])->name('templates.update');
        Route::delete('/{id}', [TemplateController::class, 'destroy'])->name('templates.destroy');
    });

    // Gerenciamento de Usuários
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.index');
        Route::post('/', [UserController::class, 'store'])->name('users.store');
        Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Gerenciamento de Empresas
    Route::prefix('companies')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('companies.index');
        Route::post('/', [CompanyController::class, 'store'])->name('companies.store');
        Route::put('/{id}', [CompanyController::class, 'update'])->name('companies.update');
        Route::delete('/{id}', [CompanyController::class, 'destroy'])->name('companies.destroy');
    });

    // Relatórios
    Route::prefix('reports')->group(function () {
        Route::get('/okr', [ReportController::class, 'okrReport'])->name('reports.okr');
        Route::get('/kpi', [ReportController::class, 'kpiReport'])->name('reports.kpi');
    });
});
