<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// "/" decides where to go (also avoids a redirect loop with the guest middleware)
Route::get('/', function () {
    if (! auth()->check()) return redirect()->route('login');
    return redirect()->route(auth()->user()->isAdmin() ? 'admin.dashboard' : 'student.dashboard');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ---------- Student ----------
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/my-loans', [StudentController::class, 'loans'])->name('student.loans');
    Route::get('/wishlist', [StudentController::class, 'wishlist'])->name('student.wishlist');
    Route::post('/loans', [StudentController::class, 'requestLoan'])->name('loans.store');

    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    Route::post('/wishlist/{book}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
});

// ---------- Admin ----------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\LoanController::class, 'dashboard'])->name('dashboard');

    Route::resource('users', Admin\UserController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('books', Admin\BookController::class);
    Route::resource('categories', Admin\CategoryController::class)->only(['index', 'store', 'update', 'destroy']);

    Route::get('/loan-requests', [Admin\LoanController::class, 'requests'])->name('loans.requests');
    Route::get('/loan-history', [Admin\LoanController::class, 'history'])->name('loans.history');
    Route::post('/loans/{loan}/approve', [Admin\LoanController::class, 'approve'])->name('loans.approve');
    Route::post('/loans/{loan}/decline', [Admin\LoanController::class, 'decline'])->name('loans.decline');
    Route::post('/loans/{loan}/return', [Admin\LoanController::class, 'markReturned'])->name('loans.return');
});
