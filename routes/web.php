<?php

use App\Livewire\Dashboard;
use App\Livewire\Profile;
use App\Livewire\ReportWorkspace;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

Route::get('/dashboard', Dashboard::class)->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('roles', function () {
        return view('admin.roles.index');
    })->middleware('permission:role-list')->name('roles.index');

    Route::resource('roles', RoleController::class)->except(['index']);

    Route::get('users', function () {
        return view('admin.users.index');
    })->middleware('permission:user-list')->name('users.index');

    Route::resource('users', UserController::class)->except(['index']);

    Route::get('reports', function () {
        return view('reports.index');
    })->middleware('permission:report-list')->name('reports.index');

    Route::get('reports/create', [ReportController::class, 'create'])
        ->middleware('permission:report-create')
        ->name('reports.create');

    Route::post('reports', [ReportController::class, 'store'])
        ->middleware('permission:report-create')
        ->name('reports.store');

    Route::get('reports/{report}', ReportWorkspace::class)
        ->middleware('permission:report-list')
        ->name('reports.show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', Profile::class)->name('profile.index');
});

require __DIR__.'/auth.php';
