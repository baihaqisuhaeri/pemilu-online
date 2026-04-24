<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__.'/auth.php';

// Sementara tanpa middleware role dulu
Route::middleware(['auth'])->group(function () {
    Route::get('/voting', [VotingController::class, 'index'])->name('voting');
    // Presiden
    Route::get('/voting/presiden', [VotingController::class, 'presiden'])->name('voting.presiden');
    Route::post('/voting/presiden', [VotingController::class, 'storePresiden'])->name('voting.presiden.store');

    // DPR
    Route::get('/voting/dpr', [VotingController::class, 'dpr'])->name('voting.dpr');
    Route::post('/voting/dpr', [VotingController::class, 'storeDpr'])->name('voting.dpr.store');

    // DPD
    Route::get('/voting/dpd', [VotingController::class, 'dpd'])->name('voting.dpd');
    Route::post('/voting/dpd', [VotingController::class, 'storeDpd'])->name('voting.dpd.store');

    Route::get('/voting/success', [VotingController::class, 'success'])->name('voting.success');

    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/export', [AdminController::class, 'export'])->name('admin.export');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
//ini kayanya ga dipake
    Route::get('/admin/candidates', [AdminController::class, 'candidates'])->name('admin.candidates');
    Route::post('/admin/candidates', [AdminController::class, 'storeCandidate'])->name('admin.candidates.store');
    Route::delete('/admin/candidates/{candidate}', [AdminController::class, 'destroyCandidate'])->name('admin.candidates.destroy');
//
    Route::get('/admin/register', [AdminController::class, 'registerForm'])->name('admin.register');
    Route::post('/admin/register', [AdminController::class, 'registerStore'])->name('admin.register.store');

     // Presiden & DPD
    Route::get('/admin/candidates', [AdminController::class, 'candidates'])->name('admin.candidates');
    Route::post('/admin/candidates', [AdminController::class, 'storeCandidate'])->name('admin.candidates.store');
    Route::delete('/admin/candidates/{candidate}', [AdminController::class, 'destroyCandidate'])->name('admin.candidates.destroy');

    // DPR
    Route::get('/admin/dpr', [AdminController::class, 'dpr'])->name('admin.dpr');
    Route::post('/admin/dpr', [AdminController::class, 'storeDpr'])->name('admin.dpr.store');
    Route::delete('/admin/dpr/{dprCandidate}', [AdminController::class, 'destroyDpr'])->name('admin.dpr.destroy');

    // Register
    Route::get('/admin/register', [AdminController::class, 'registerForm'])->name('admin.register');
    Route::post('/admin/register', [AdminController::class, 'registerStore'])->name('admin.register.store');

    //Reset Voting
    Route::post('/admin/reset-voting', [AdminController::class, 'resetVoting'])->name('admin.reset.voting');
    Route::post('/admin/reset-voting/{type}', [AdminController::class, 'resetVotingByType'])->name('admin.reset.voting.type');
});

require __DIR__.'/auth.php';
