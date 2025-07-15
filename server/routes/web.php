<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.login');
})->name('login');

Route::get('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);

Route::get('/interests', function () {
    return view('pages.interests');
})->name('interests');

Route::get('/question', function () {
    return view('pages.question');
})->name('question');

Route::get('/user-profile', function () {
    return view('pages.user_profile');
})->name('user_profile');

Route::get('/user-information', function () {
    return view('pages.user_information');
})->name('user_information');

Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/user-information', [\App\Http\Controllers\UserOnboardingController::class, 'showUserInfoForm'])->name('user.information');
    Route::post('/user-information', [\App\Http\Controllers\UserOnboardingController::class, 'submitUserInfo']);
    Route::get('/user-interests', [\App\Http\Controllers\UserOnboardingController::class, 'showInterestsForm'])->name('user.interests');
    Route::post('/user-interests', [\App\Http\Controllers\UserOnboardingController::class, 'submitInterests']);
});
