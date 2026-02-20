<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Explore Kerinci
|--------------------------------------------------------------------------
*/

// ─── Public Routes (no token required) ────────────────────────────────────────

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::get('destinations', [DestinationController::class, 'index']);
Route::get('destinations/{slug}', [DestinationController::class, 'show']);
Route::get('categories', [CategoryController::class, 'index']);

// ─── Protected Routes (Sanctum token required) ────────────────────────────────

Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('reviews', [ReviewController::class, 'store']);

    // User Profile
    Route::get('user/profile', [UserProfileController::class, 'show']);
    Route::post('user/profile', [UserProfileController::class, 'update']);
    Route::get('user/reviews', [UserProfileController::class, 'reviews']);
});

// ─── Admin Routes (Sanctum + admin role required) ─────────────────────────────

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {

    // Destinations CRUD
    Route::get('destinations', [Admin\DestinationController::class, 'index']);
    Route::post('destinations', [Admin\DestinationController::class, 'store']);
    Route::get('destinations/{destination}', [Admin\DestinationController::class, 'show']);
    Route::put('destinations/{destination}', [Admin\DestinationController::class, 'update']);
    Route::delete('destinations/{destination}', [Admin\DestinationController::class, 'destroy']);

    // Reviews — list, approve, reject, delete
    Route::get('reviews', [Admin\ReviewController::class, 'index']);
    Route::patch('reviews/{review}/approve', [Admin\ReviewController::class, 'approve']);
    Route::patch('reviews/{review}/reject', [Admin\ReviewController::class, 'reject']);
    Route::delete('reviews/{review}', [Admin\ReviewController::class, 'destroy']);

    // Categories CRUD
    Route::get('categories', [Admin\CategoryController::class, 'index']);
    Route::post('categories', [Admin\CategoryController::class, 'store']);
    Route::put('categories/{category}', [Admin\CategoryController::class, 'update']);
    Route::delete('categories/{category}', [Admin\CategoryController::class, 'destroy']);

    // Destination Photos
    Route::post('destinations/{id}/photos', [PhotoController::class, 'store']);
    Route::delete('destinations/{destinationId}/photos/{photoId}', [PhotoController::class, 'destroy']);
});
