<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    //Proposal Controller
    Route::get('/proposal', [ProposalController::class, 'index'])->name('proposal.index');
    Route::get('/proposal/create', [ProposalController::class, 'showProposalForm'])->name('proposal.create');
    Route::post('/proposal/create', [ProposalController::class, 'store'])->name('proposal.store');
    Route::get('/proposal/{proposal}', [ProposalController::class, 'view'])->name('proposal.view');
    Route::put('/proposal/{proposal}/update', [ProposalController::class, 'update'])->name('proposal.update');
    Route::delete('/proposal/{proposal}/destroy', [ProposalController::class, 'destroy'])->name('proposal.destroy');
    Route::get('/proposal/{proposal}/download-TP', [ProposalController::class, 'downloadTP'])->name('proposal.downloadTP');
    Route::get('/proposal/{proposal}/display-TP', [ProposalController::class, 'displayTP'])->name('proposal.displayTP');
    Route::get('/proposal/{proposal}/download-JMS', [ProposalController::class, 'downloadJMS'])->name('proposal.downloadJMS');
    Route::get('/proposal/{proposal}/display-JMS', [ProposalController::class, 'displayJMS'])->name('proposal.displayJMS');
    Route::patch('/proposal/{proposal}/review-status', [ProposalController::class, 'updateReviewStatus'])->name('proposal.updateReviewStatus');
    Route::patch('/proposal/{proposal}/check-status', [ProposalController::class, 'updateCheckedStatus'])->name('proposal.updateCheckedStatus');
    Route::patch('/proposal/{proposal}/approve-status', [ProposalController::class, 'updateApprovedStatus'])->name('proposal.updateApprovedStatus');
    
    //Company Controller
    Route::get('/companies', [CompanyController::class, 'index'])->name('company.index');
    Route::post('/company/register', [CompanyController::class, 'store'])->name('company.store');
    Route::get('/company/register', [CompanyController::class, 'showCompanyForm'])->name('company.register');
    Route::get('/company/{company}', [CompanyController::class, 'viewCompany'])->name('company.view');

    //Project Controller
    Route::get('/projects', [ProjectController::class, 'index'])->name('project.index');
    Route::post('/project/create', [ProjectController::class, 'store'])->name('project.store');
    Route::get('/project/create', [ProjectController::class, 'showProjectForm'])->name('project.create');
    Route::get('/project/{project}', [ProjectController::class, 'viewProject'])->name('project.view');

    //Profile Controller
    Route::get('/profile', [ProfileController::class, 'showProfilePage'])->name('profile.view');

    //Notification Controller
    Route::get('/notifications/unread', [NotificationController::class, 'getUnreadNotifications'])->name('notifications.unread');
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::post('/notifications/{notification}/mark-as-read-single', [NotificationController::class, 'markSingleAsRead'])->name('notifications.markSingleAsRead');

    //Admin Controller
    Route::middleware('admin')->group(function () {
        Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users');
        Route::get('/admin/users/{account}', [AdminController::class, 'viewUser'])->name('admin.users.view');
        Route::patch('/admin/users/{account}/verify-status', [AdminController::class, 'updateVerificationStatus'])->name('admin.users.updateVerificationStatus');
        Route::patch('/admin/users/{account}/account-status', [AdminController::class, 'updateAccountStatus'])->name('admin.users.updateAccountStatus');
    }); 
});

//Account Controller
Route::get('/register', [AccountController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AccountController::class, 'store'])->name('user.store');

//Auth Controller
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('post.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

