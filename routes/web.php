<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

// ─── Public routes ───────────────────────────────────────────────────────────

Route::get('/', [ExploreController::class, 'index'])->name('explore');
Route::get('/users/{user}', [UserProfileController::class, 'show'])->name('users.show');

// ─── Guest-only routes ───────────────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

// ─── Authenticated routes ────────────────────────────────────────────────────

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Bookmark dashboard
    Route::get('/dashboard', [BookmarkController::class, 'index'])->name('bookmarks.index');

    Route::resource('bookmarks', BookmarkController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy']);

    Route::post('bookmarks/{bookmark}/favorite', [BookmarkController::class, 'toggleFavorite'])
        ->name('bookmarks.favorite');

    Route::post('bookmarks/{bookmark}/public', [BookmarkController::class, 'togglePublic'])
        ->name('bookmarks.public');

    // Save a public bookmark to own collection
    Route::post('explore/{bookmark}/save', [ExploreController::class, 'save'])
        ->name('explore.save');
});
