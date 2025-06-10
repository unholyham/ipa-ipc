<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    //Proposal Controller
    Route::get('/proposal', [ProposalController::class, 'index'])->name('proposal.index');
    Route::get('/proposal/create', [ProposalController::class, 'create'])->name('proposal.create');
    Route::post('/proposal', [ProposalController::class, 'store'])->name('proposal.store');
    Route::get('/proposal/{proposal}', [ProposalController::class, 'view'])->name('proposal.view');
    Route::put('/proposal/{proposal}/update', [ProposalController::class, 'update'])->name('proposal.update');
    Route::delete('/proposal/{proposal}/destroy', [ProposalController::class, 'destroy'])->name('proposal.destroy');
    Route::get('/proposal/{proposal}/download-TP', [ProposalController::class, 'downloadTP'])->name('proposal.downloadTP');
    Route::get('/proposal/{proposal}/display-TP', [ProposalController::class, 'displayTP'])->name('proposal.displayTP');
    Route::get('/proposal/{proposal}/download-JMS', [ProposalController::class, 'downloadJMS'])->name('proposal.downloadJMS');
    Route::get('/proposal/{proposal}/display-JMS', [ProposalController::class, 'displayJMS'])->name('proposal.displayJMS');
    Route::patch('/proposal/{proposal}/review-status', [ProposalController::class, 'updateReviewStatus'])->name('proposal.updateReviewStatus');
    Route::patch('/proposal/{proposal}/approve-status', [ProposalController::class, 'updateApprovedStatus'])->name('proposal.updateApprovedStatus');
    
    //Profile Controller
    Route::get('/profile', [ProfileController::class, 'showProfilePage'])->name('profile.view');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    //Notification Controller
    Route::get('/notifications/unread', [NotificationController::class, 'getUnreadNotifications'])->name('notifications.unread');
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::post('/notifications/{notification}/mark-as-read-single', [NotificationController::class, 'markSingleAsRead'])->name('notifications.markSingleAsRead');

    //Admin Controller
    Route::middleware('admin')->group(function () {
        Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users');
        Route::get('/admin/users/{user}', [AdminController::class, 'viewUser'])->name('admin.users.view');
        Route::patch('/admin/users/{user}/verify-status', [AdminController::class, 'updateVerificationStatus'])->name('admin.users.updateVerificationStatus');
        Route::patch('/admin/users/{user}/account-status', [AdminController::class, 'updateAccountStatus'])->name('admin.users.updateAccountStatus');
    }); 
});

//User Controller
Route::get('/register', [UserController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [UserController::class, 'register'])->name('user.store');

//Auth Controller
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('post.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

