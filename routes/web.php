<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserApprovalController;
Route::get('/', function(){ return view('welcome'); })->name('welcome');
Route::middleware(['auth'])->group(function(){
    Route::get('/pending', fn() => view('pending'))->name('pending');
});
Route::middleware(['auth','verified','approved'])->group(function(){
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
    Route::get('/summary', [SummaryController::class,'index'])->name('summary.index');
    Route::get('/reports', [ReportController::class,'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class,'export'])->name('reports.export');
    Route::get('/categories', [CategoryController::class,'index'])->name('categories.index');
    Route::middleware('role:admin')->group(function(){
        Route::post('/categories', [CategoryController::class,'store'])->name('categories.store');
        Route::put('/categories/{category}', [CategoryController::class,'update'])->name('categories.update');
        Route::patch('/categories/{category}', [CategoryController::class,'update']);
        Route::delete('/categories/{category}', [CategoryController::class,'destroy'])->name('categories.destroy');
    });
    Route::resource('transactions', TransactionController::class)->only(['index','store','update','destroy']);
    Route::post('/transactions/{transaction}/attachments', [AttachmentController::class,'store'])->name('attachments.store');
    Route::delete('/attachments/{attachment}', [AttachmentController::class,'destroy'])->name('attachments.destroy');
    Route::get('/attachments/{attachment}', [AttachmentController::class,'show'])->name('attachments.show');
    Route::middleware('role:admin')->group(function(){
        Route::get('/admin/users', [UserApprovalController::class,'index'])->name('admin.users.index');
        Route::post('/admin/users/{user}/approve', [UserApprovalController::class,'approve'])->name('admin.users.approve');
        Route::delete('/admin/users/{user}/reject', [UserApprovalController::class,'reject'])->name('admin.users.reject');
        Route::patch('/admin/users/{user}/role', [UserApprovalController::class,'updateRole'])->name('admin.users.role');
        Route::get('/admin/transaction-edits', [\App\Http\Controllers\Admin\TransactionApprovalController::class,'index'])->name('admin.transactions.index');
        Route::post('/admin/transaction-edits/{request}/approve', [\App\Http\Controllers\Admin\TransactionApprovalController::class,'approve'])->name('admin.transactions.approve');
        Route::post('/admin/transaction-edits/{request}/reject', [\App\Http\Controllers\Admin\TransactionApprovalController::class,'reject'])->name('admin.transactions.reject');
    });
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__.'/auth.php';
