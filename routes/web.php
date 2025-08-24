<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SuratMasukController;
use App\Http\Controllers\Admin\DisposisiController;
use App\Http\Controllers\Admin\SuratKeluarController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\ProfileController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    // ======== SHARED (ADMIN + USER) ========
    Route::prefix('admin')->name('admin.')->group(function () {
        // LIST
        Route::get('/surat-masuk',  [SuratMasukController::class,  'index'])->name('surat-masuk.index');
        Route::get('/surat-keluar', [SuratKeluarController::class, 'index'])->name('surat-keluar.index');
        Route::get('/disposisi',    [DisposisiController::class,   'index'])->name('disposisi.index');

        // CREATE
        Route::post('/surat-masuk',  [SuratMasukController::class,  'store'])
            ->middleware('can:create,App\Models\SuratMasuk')->name('surat-masuk.store');
        Route::post('/surat-keluar', [SuratKeluarController::class, 'store'])
            ->middleware('can:create,App\Models\SuratKeluar')->name('surat-keluar.store');
        Route::post('/disposisi',    [DisposisiController::class,   'store'])
            ->middleware('can:create,App\Models\Disposisi')->name('disposisi.store');

        // UPDATE/DELETE (User & Admin via Policy)
        Route::put('/surat-masuk/{suratMasuk}',    [SuratMasukController::class, 'update'])
            ->middleware('can:update,suratMasuk')->name('surat-masuk.update');
        Route::delete('/surat-masuk/{suratMasuk}', [SuratMasukController::class, 'destroy'])
            ->middleware('can:delete,suratMasuk')->name('surat-masuk.destroy');

        // Surat Keluar delete — authorize di controller (param {id})
        Route::delete('/surat-keluar/{id}', [SuratKeluarController::class, 'destroy'])
            ->name('surat-keluar.destroy');
    });

    // ======== ADMIN ONLY (tetap) ========
    Route::prefix('admin')->name('admin.')->middleware('isAdmin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // User Management
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::patch('/users/{user}/role',   [UserManagementController::class, 'updateRole'])->name('users.updateRole');
        Route::patch('/users/{user}/status', [UserManagementController::class, 'toggleStatus'])->name('users.toggleStatus');
        Route::delete('/users/{user}',       [UserManagementController::class, 'destroy'])->name('users.destroy');
    });

    // Dashboard user
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
        ->name('dashboard');

    // Profil
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile',[ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.updateAvatar');
});

// root -> login
Route::get('/', fn () => redirect()->route('login'));
