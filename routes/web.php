<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RoleSwitchController;
use App\Http\Controllers\GlobalCopilotController;
use App\Http\Controllers\Admin\SettingController;

// The "Traffic Cop" Gateway Dashboard
use App\Http\Controllers\DashboardController;

// Role-Specific Dashboard Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboard;
use App\Http\Controllers\Counselor\DashboardController as CounselorDashboard;
use App\Http\Controllers\SchoolHead\DashboardController as HeadDashboard;

// Teacher Controllers
use App\Http\Controllers\Teacher\DailyLessonLogController;
use App\Http\Controllers\Teacher\PortfolioController;
use App\Http\Controllers\Teacher\CotController as TeacherCotController;
use App\Http\Controllers\Teacher\IncidentController;
use App\Http\Controllers\Teacher\TeacherInventoryController;
use App\Http\Controllers\Teacher\ClearanceController as TeacherClearance;

// Admin Controllers
use App\Http\Controllers\Admin\PersonnelController;
use App\Http\Controllers\Admin\LisSyncController;
use App\Http\Controllers\Admin\AdminInventoryController;
use App\Http\Controllers\Admin\ClearanceController as AdminClearance;


// Counselor Controllers
use App\Http\Controllers\Counselor\IncidentReviewController;
use App\Http\Controllers\Counselor\ViolatorController;
use App\Http\Controllers\Counselor\GoodMoralController;

// School Head Controllers
use App\Http\Controllers\SchoolHead\DllApprovalController;
use App\Http\Controllers\SchoolHead\DssAnalyticsController;
use App\Http\Controllers\SchoolHead\CotController as HeadCotController;
use App\Http\Controllers\SchoolHead\BehavioralOversightController;
use App\Http\Controllers\SchoolHead\SchoolheadInventoryController;
use App\Http\Controllers\SchoolHead\ReportController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Redirect root to login
Route::get('/', fn () => redirect()->route('login'));

Route::post('/copilot/global', [GlobalCopilotController::class, 'handlePrompt'])
    ->middleware('auth')
    ->name('copilot.global');

// Authenticated & Active User Routes
Route::middleware(['auth', 'active'])->group(function () {

    // 1. THE GATEWAY ROUTE
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Workspace Selection & Role Switcher
    Route::get('/role/select', function () {
        if (Auth::user()->roles()->count() <= 1 && Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }
        return view('auth.role-select');
    })->name('role.select');

    Route::post('/role/switch/{role}', [RoleSwitchController::class, 'switch'])->name('role.switch');

    // ==========================================
    // ADMIN GROUP
    // ==========================================
    Route::prefix('admin')->name('admin.')->middleware(['role:admin'])->group(function () {
        Route::get('dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Settings / School Profile (NEW)
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');

        // Personnel Management
        Route::resource('personnel', PersonnelController::class)->only(['index', 'store', 'edit', 'update', 'destroy']);
        Route::patch('personnel/{personnel}/toggle', [PersonnelController::class, 'toggleStatus'])->name('personnel.toggleStatus');

        // LIS Sync
        Route::get('lis-sync', [LisSyncController::class, 'index'])->name('lis_sync.index');
        Route::post('lis-sync/upload', [LisSyncController::class, 'upload'])->name('lis_sync.upload');
        Route::get('lis-sync/manage', [LisSyncController::class, 'manage'])->name('lis_sync.manage');
        Route::get('lis-sync/reset', [LisSyncController::class, 'resetForm'])->name('lis_sync.reset');
        Route::delete('lis-sync/reset', [LisSyncController::class, 'resetYear'])->name('lis_sync.reset.year');
        Route::get('lis-sync/{lisStaging}/edit', [LisSyncController::class, 'edit'])->name('lis_sync.edit');
        Route::put('lis-sync/{lisStaging}', [LisSyncController::class, 'update'])->name('lis_sync.update');
        Route::delete('lis-sync/{lisStaging}', [LisSyncController::class, 'destroy'])->name('lis_sync.destroy');

        // Inventory & Clearances
        // Inventory & Clearances
Route::resource('inventory', AdminInventoryController::class);
        Route::get('clearances', [AdminClearance::class, 'index'])->name('clearances.index');
    });

    // ==========================================
    // TEACHER GROUP
    // ==========================================
    Route::prefix('teacher')->name('teacher.')->middleware(['role:teacher|admin'])->group(function () {
        Route::get('dashboard', [TeacherDashboard::class, 'index'])->name('dashboard');

        // COT Routes
        Route::get('/cot', [TeacherCotController::class, 'index'])->name('cot.index');
        Route::post('/cot/request', [TeacherCotController::class, 'storeRequest'])->name('cot.request');
        Route::get('/cot/{id}', [TeacherCotController::class, 'show'])->name('cot.show');

        // DLL Routes
        Route::get('dll/{dll}/week', [DailyLessonLogController::class, 'week'])->name('dll.week');
        Route::post('dll/{dll}/entries/{date}', [DailyLessonLogController::class, 'saveEntry'])->name('dll.entry.save');
        Route::post('dll/offline/import', [DailyLessonLogController::class, 'importOffline'])->name('dll.offline.import');
        Route::post('dll/precheck', [DailyLessonLogController::class, 'precheck'])->name('dll.precheck');
        Route::post('dll/profile', [DailyLessonLogController::class, 'storeProfile'])->name('dll.profile.store');
        Route::post('dll/{dll}/copilot', [DailyLessonLogController::class, 'coPilotChat'])->name('dll.copilot');
        Route::post('/dll/{dll}/ai/scan', [App\Http\Controllers\Teacher\DailyLessonLogController::class, 'scanCompliance'])->name('teacher.dll.ai.scan');
        Route::put('dll/{dll}/submit', [DailyLessonLogController::class, 'submit'])->name('dll.submit');
        Route::put('dll/{dll}/resubmit', [DailyLessonLogController::class, 'resubmit'])->name('dll.resubmit');
        Route::resource('dll', DailyLessonLogController::class);

        // Portfolio
        Route::prefix('portfolio')->name('portfolio.')->group(function () {
            Route::get('/', [PortfolioController::class, 'index'])->name('index');
            Route::get('/create', [PortfolioController::class, 'create'])->name('create');
            Route::post('/', [PortfolioController::class, 'store'])->name('store');
            Route::delete('/{portfolio}', [PortfolioController::class, 'destroy'])->name('destroy');
        });

        // Teacher Asset Management
        Route::get('/inventory', [TeacherInventoryController::class, 'index'])->name('inventory.index');
Route::get('/inventory/{inventory}/damage', [TeacherInventoryController::class, 'reportDamage'])->name('inventory.damage');
Route::post('/inventory/{inventory}/damage', [TeacherInventoryController::class, 'storeDamage'])->name('inventory.damage.store');
Route::get('/inventory/{inventory}/borrow', [TeacherInventoryController::class, 'logBorrow'])->name('inventory.borrow');
Route::post('/inventory/{inventory}/borrow', [TeacherInventoryController::class, 'storeBorrow'])->name('inventory.borrow.store');
});

    // ==========================================
    // COUNSELOR GROUP
    // ==========================================
    Route::prefix('counselor')->name('counselor.')->middleware(['role:counselor|admin'])->group(function () {
        Route::get('dashboard', [CounselorDashboard::class, 'index'])->name('dashboard');

        // Incident Management
        Route::get('incidents', [IncidentReviewController::class, 'index'])->name('incidents.index');
        Route::get('incidents/search-lis', [IncidentReviewController::class, 'searchLis'])->name('incidents.search_lis');
        Route::get('incidents/create', [IncidentReviewController::class, 'create'])->name('incidents.create');
        Route::post('incidents', [IncidentReviewController::class, 'store'])->name('incidents.store');
        Route::get('incidents/{incident}/edit', [IncidentReviewController::class, 'edit'])->name('incidents.edit');
        Route::put('incidents/{incident}', [IncidentReviewController::class, 'update'])->name('incidents.update');
        Route::delete('incidents/{incident}', [IncidentReviewController::class, 'destroy'])->name('incidents.destroy');
        Route::put('incidents/{incident}/resolve', [IncidentReviewController::class, 'resolve'])->name('incidents.resolve');

        // Violator Directory
        Route::get('violators', [ViolatorController::class, 'index'])->name('violators.index');
        Route::get('violators/{student}', [ViolatorController::class, 'show'])->name('violators.show');

        // Good Moral Audits
        Route::get('good-moral', [GoodMoralController::class, 'index'])->name('good_moral.index');
        Route::post('good-moral/generate', [GoodMoralController::class, 'generate'])->name('good_moral.generate');
        Route::post('good-moral/batch', [GoodMoralController::class, 'generateBatch'])->name('good_moral.batch');
        Route::post('good-moral/blocked/export', [GoodMoralController::class, 'exportBlocked'])->name('good_moral.blocked.export');
    });

    // ==========================================
    // SCHOOL HEAD GROUP
    // ==========================================
    Route::prefix('school-head')->name('school_head.')->middleware(['role:school_head|admin'])->group(function () {
        Route::get('dashboard', [HeadDashboard::class, 'index'])->name('dashboard');

        // DLL / Instructional Supervision
        Route::get('dll-approvals', [DllApprovalController::class, 'index'])->name('dll.index');
        Route::get('dll-approvals/export', [DllApprovalController::class, 'exportChecklist'])->name('dll.export');
        Route::get('dll-approvals/{dll}/show', [DllApprovalController::class, 'show'])->name('dll.show');
        Route::post('dll-approvals/{dll}/ai-brief', [DllApprovalController::class, 'generateAiBrief'])->name('dll.ai.brief');
        Route::put('dll-approvals/{dll}/approve', [DllApprovalController::class, 'markAsChecked'])->name('dll.approve');
        Route::put('dll-approvals/{dll}/return', [DllApprovalController::class, 'provideTechnicalAssistance'])->name('dll.return');

        // COT Oversight
        Route::get('/cot', [HeadCotController::class, 'index'])->name('cot.index');
        Route::post('/cot/scan', [HeadCotController::class, 'scan'])->name('cot.scan');
        Route::get('/cot/create/{teacher}', [HeadCotController::class, 'create'])->name('cot.create');
        Route::post('/cot/{teacher}', [HeadCotController::class, 'store'])->name('cot.store');
        Route::get('/cot/blank', [HeadCotController::class, 'blankForm'])->name('cot.blank');
        Route::get('/cot/show/{id}', [HeadCotController::class, 'show'])->name('cot.show');

        // DSS Analytics
        Route::get('dss-analytics', [DssAnalyticsController::class, 'index'])->name('dss.index');

        // Behavioral Oversight
        Route::prefix('behavioral-oversight')->name('behavior.')->group(function () {
            Route::get('analytics', [BehavioralOversightController::class, 'analytics'])->name('analytics');
            Route::get('cases', [BehavioralOversightController::class, 'cases'])->name('cases');
            Route::get('cases/{incident}', [BehavioralOversightController::class, 'show'])->name('show');
            Route::put('cases/{incident}', [BehavioralOversightController::class, 'update'])->name('update');
            Route::put('cases/{incident}/resolve', [BehavioralOversightController::class, 'resolve'])->name('resolve');
            Route::put('cases/{incident}/override', [BehavioralOversightController::class, 'override'])->name('override'); // FIXED: Override route present
            Route::get('good-moral', [BehavioralOversightController::class, 'goodMoral'])->name('good_moral');
        });

        Route::get('inventory-oversight', [App\Http\Controllers\SchoolHead\SchoolheadInventoryController::class, 'index'])->name('inventory.index');
        Route::put('inventory-oversight/{id}/approve', [App\Http\Controllers\SchoolHead\SchoolheadInventoryController::class, 'approveDisposal'])->name('inventory.approve_disposal');
        Route::put('inventory-oversight/{id}/reject', [App\Http\Controllers\SchoolHead\SchoolheadInventoryController::class, 'rejectDisposal'])->name('inventory.reject_disposal');
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::post('reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    });

    // Profile Management (Global)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
