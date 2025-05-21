<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/proposal', [ProposalController::class, 'index'])->name('proposal.index');
    Route::get('/proposal/create', [ProposalController::class, 'create'])->name('proposal.create');
    Route::post('/proposal', [ProposalController::class, 'store'])->name('proposal.store');
    Route::get('/proposal/{proposal}', [ProposalController::class, 'view'])->name('proposal.view');
    Route::put('/proposal/{proposal}/update', [ProposalController::class, 'update'])->name('proposal.update');
    Route::delete('/proposal/{proposal}/destroy', [ProposalController::class, 'destroy'])->name('proposal.destroy');
    Route::get('/proposal/{proposal}/download-pdf', [ProposalController::class, 'downloadPdf'])->name('proposal.downloadPdf');
    Route::get('/proposal/{proposal}/display-pdf', [ProposalController::class, 'displayPdf'])->name('proposal.displayPdf');
    Route::patch('/proposal/{proposal}/review-status', [ProposalController::class, 'updateReviewStatus'])->name('proposal.updateReviewStatus');
    Route::patch('/proposal/{proposal}/approve-status', [ProposalController::class, 'updateApprovedStatus'])->name('proposal.updateApprovedStatus');
});

Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [UserController::class, 'register'])->name('user.store');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('post.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/profile', [ProfileController::class, 'showProfilePage'])->name('profile.view');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');