<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
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

Route::get('/questions', [QuestionController::class, 'index'])->name('questions');
Route::get('/question/{id?}', [QuestionController::class, 'show'])->name('question');

// Question routes
Route::middleware(['auth'])->group(function () {
    Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::get('/questions/{id}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::put('/questions/{id}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/questions/{id}', [QuestionController::class, 'destroy'])->name('questions.destroy');
    Route::post('/questions/{id}/upvote', [QuestionController::class, 'upvote'])->name('questions.upvote');
    Route::post('/questions/{id}/downvote', [QuestionController::class, 'downvote'])->name('questions.downvote');
    Route::post('/questions/{id}/bookmark', [QuestionController::class, 'bookmark'])->name('questions.bookmark');
    Route::post('/questions/{id}/share', [QuestionController::class, 'share'])->name('questions.share');
    
    // Answer routes
    Route::post('/questions/{questionId}/answers', [AnswerController::class, 'store'])->name('answers.store');
    Route::put('/answers/{id}', [AnswerController::class, 'update'])->name('answers.update');
    Route::delete('/answers/{id}', [AnswerController::class, 'destroy'])->name('answers.destroy');
    Route::post('/answers/{id}/upvote', [AnswerController::class, 'upvote'])->name('answers.upvote');
    Route::post('/answers/{id}/downvote', [AnswerController::class, 'downvote'])->name('answers.downvote');
    Route::post('/answers/{id}/accept', [AnswerController::class, 'accept'])->name('answers.accept');
    Route::post('/answers/{id}/comment', [AnswerController::class, 'comment'])->name('answers.comment');
    Route::post('/answers/{id}/share', [AnswerController::class, 'share'])->name('answers.share');
    
    // Post/Insight routes
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{id}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{id}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/ajax', [PostController::class, 'storeAjax'])->name('posts.store.ajax');
    Route::post('/posts/{id}/upvote', [PostController::class, 'upvote'])->name('posts.upvote');
    Route::post('/posts/{id}/downvote', [PostController::class, 'downvote'])->name('posts.downvote');
    Route::get('/posts/{id}/vote-status', [PostController::class, 'getUserVoteStatus'])->name('posts.vote-status');
    Route::post('/posts/{id}/bookmark', [PostController::class, 'bookmark'])->name('posts.bookmark');
    Route::get('/posts/{id}/bookmark-status', [PostController::class, 'getUserBookmarkStatus'])->name('posts.bookmark-status');
    
    // Comment routes
    Route::post('/posts/{postId}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{id}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

Route::get('/user-profile', function () {
    return view('pages.user_profile');
})->name('user_profile');

Route::get('/user-information', function () {
    return view('pages.user_information');
})->name('user_information');

Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->name('dashboard');

// API route for dashboard posts (accessible without authentication)
Route::get('/api/dashboard/posts', [PostController::class, 'getDashboardPosts'])->name('api.dashboard.posts');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');
    Route::get('/answers', [ProfileController::class, 'answers'])->name('answers');
    Route::get('/questions', [ProfileController::class, 'questions'])->name('questions');
    Route::get('/posts', [ProfileController::class, 'posts'])->name('posts');
    Route::get('/followers', [ProfileController::class, 'followers'])->name('followers');
    Route::get('/following', [ProfileController::class, 'following'])->name('following');
    Route::get('/bookmarks', [ProfileController::class, 'bookmarks'])->name('bookmarks');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/user-information', [\App\Http\Controllers\UserOnboardingController::class, 'showUserInfoForm'])->name('user.information');
    Route::post('/user-information', [\App\Http\Controllers\UserOnboardingController::class, 'submitUserInfo']);
    Route::get('/user-interests', [\App\Http\Controllers\UserOnboardingController::class, 'showInterestsForm'])->name('user.interests');
    Route::post('/user-interests', [\App\Http\Controllers\UserOnboardingController::class, 'submitInterests']);
});
