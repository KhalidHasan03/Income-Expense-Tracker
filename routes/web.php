<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\ProfileController;
Route::get('/', function(){ return view('welcome'); })->name('welcome');
Route::middleware(['auth','verified'])->group(function(){
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
    Route::get('/summary', [SummaryController::class,'index'])->name('summary.index');
    Route::resource('categories', CategoryController::class)->only(['index','store','update','destroy']);
    Route::resource('transactions', TransactionController::class)->only(['index','store','update','destroy']);
});
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__.'/auth.php';
