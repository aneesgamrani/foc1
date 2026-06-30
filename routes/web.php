<?php

use App\Livewire\Dashboard;
use App\Livewire\Profile;
use App\Livewire\ReportWorkspace;
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

    Route::get('users', function () {
        return view('admin.users.index');
    })->middleware('permission:user-list')->name('users.index');

    Route::get('reports', function () {
        return view('reports.index');
    })->middleware('permission:report-list')->name('reports.index');

    Route::get('reports/{report}', ReportWorkspace::class)
        ->middleware('permission:report-list')
        ->name('reports.show');

    Route::get('/profile', Profile::class)->name('profile.index');
});

require __DIR__.'/auth.php';