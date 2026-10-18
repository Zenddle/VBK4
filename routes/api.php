<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\LoanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // katalog: semua pengguna yang login
    Route::get('/books', [BookController::class, 'index']);
    Route::get('/books/{id}', [BookController::class, 'show']);

    // mahasiswa
    Route::middleware('role:student')->group(function () {
        Route::post('/loans', [LoanController::class, 'store']);
        Route::get('/loans', [LoanController::class, 'index']);
    });

    // admin (petugas)
    Route::middleware('role:admin')->group(function () {
        Route::patch('/loans/{id}/approve', [LoanController::class, 'approve']);
        Route::patch('/loans/{id}/return', [LoanController::class, 'markReturned']);
    });
});
