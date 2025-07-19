<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Mail\ReportNotification;
use App\Models\PostReport;
use Illuminate\Support\Facades\Mail;



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
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('password.update');
});

Route::middleware(['auth'])->prefix('profile')->name('profile.')->group(function () {
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');
    Route::get('/answers', [ProfileController::class, 'answers'])->name('answers');
    Route::get('/questions', [ProfileController::class, 'questions'])->name('questions');
    Route::get('/posts', [ProfileController::class, 'posts'])->name('posts');
    Route::get('/followers', [ProfileController::class, 'followers'])->name('followers');
    Route::get('/following', [ProfileController::class, 'following'])->name('following');
    Route::get('/bookmarks', [ProfileController::class, 'bookmarks'])->name('bookmarks');
    Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
    Route::post('/settings', [ProfileController::class, 'updateSettings'])->name('settings.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/user-information', [\App\Http\Controllers\UserOnboardingController::class, 'showUserInfoForm'])->name('user.information');
    Route::post('/user-information', [\App\Http\Controllers\UserOnboardingController::class, 'submitUserInfo']);
    Route::get('/user-interests', [\App\Http\Controllers\UserOnboardingController::class, 'showInterestsForm'])->name('user.interests');
    Route::post('/user-interests', [\App\Http\Controllers\UserOnboardingController::class, 'submitInterests']);
});

Route::middleware(['auth'])->group(function () {
    Route::post('/posts/{id}/report', [PostController::class, 'report'])->name('posts.report');
    Route::post('/questions/{id}/report', [QuestionController::class, 'report'])->name('questions.report');
    Route::post('/answers/{id}/report', [AnswerController::class, 'report'])->name('answers.report');
});

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth:admin', 'check.admin.status'])->group(function () {
    // Admin Dashboard Views
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/test-email', function() {
        try {
            $report = PostReport::with('reporter')->first();
            if ($report) {
                Mail::to('kvmithilesh2005@gmail.com')->send(new ReportNotification($report, 'resolved', 'post'));
                return response()->json(['success' => true, 'message' => 'Test email sent successfully!']);
            } else {
                return response()->json(['success' => false, 'message' => 'No reports found to test with']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    })->name('admin.test.email');
    
    Route::get('/admin/test-resolve', function() {
        try {
            $report = PostReport::first();
            if ($report) {
                $report->update(['status' => 'resolved']);
                return response()->json(['success' => true, 'message' => 'Test resolve successful!', 'report_id' => $report->report_id]);
            } else {
                return response()->json(['success' => false, 'message' => 'No reports found']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    })->name('admin.test.resolve');
    Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/admin/questions', [AdminController::class, 'questions'])->name('admin.questions');
    Route::get('/admin/answers', [AdminController::class, 'answers'])->name('admin.answers');
    Route::get('/admin/posts', [AdminController::class, 'posts'])->name('admin.posts');
    Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');

    // Admin API Endpoints
    Route::prefix('admin/api')->group(function () {
        // Dashboard API
        Route::get('/dashboard/stats', [AdminController::class, 'dashboardStats']);
        Route::get('/dashboard/recent-activity', [AdminController::class, 'recentActivity']);
        Route::get('/dashboard/top-contributors', [AdminController::class, 'topContributors']);
        Route::get('/dashboard/trending-topics', [AdminController::class, 'trendingTopics']);

        // Reports API
        Route::get('/reports/stats', [AdminController::class, 'reportsStats']);
        Route::get('/reports', [AdminController::class, 'getReports']);
        Route::get('/reports/{type}/{id}', [AdminController::class, 'getReportDetails']);
        Route::put('/reports/{type}/{id}/status', [AdminController::class, 'updateReportStatus']);
        Route::delete('/reports/{type}/{id}', [AdminController::class, 'deleteReport']);
        Route::delete('/reports/{type}/{id}/content', [AdminController::class, 'deleteReportedContent']);

        // Users API
        Route::get('/users/stats', [AdminController::class, 'usersStats']);
        Route::get('/users', [AdminController::class, 'getUsers']);
        Route::post('/users', [AdminController::class, 'storeUser']);
        Route::get('/users/{id}', [AdminController::class, 'getUserDetails']);
        Route::put('/users/{id}', [AdminController::class, 'updateUser']);
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);
        Route::post('/users/{id}/ban', [AdminController::class, 'banUser']);
        Route::post('/users/{id}/unban', [AdminController::class, 'unbanUser']);
        Route::post('/users/{id}/role', [AdminController::class, 'changeUserRole']);

        // Questions API
        Route::get('/questions/stats', [AdminController::class, 'questionsStats']);
        Route::get('/questions', [AdminController::class, 'getQuestions']);
        Route::get('/questions/{id}', [AdminController::class, 'getQuestionDetails']);
        Route::put('/questions/{id}', [AdminController::class, 'updateQuestion']);
        Route::delete('/questions/{id}', [AdminController::class, 'deleteQuestion']);
        Route::post('/questions/{id}/toggle-status', [AdminController::class, 'toggleQuestionStatus']);
        Route::post('/questions/{id}/toggle-featured', [AdminController::class, 'toggleQuestionFeatured']);

        // Answers API
        Route::get('/answers/stats', [AdminController::class, 'answersStats']);
        Route::get('/answers', [AdminController::class, 'getAnswers']);

        // Posts API
        Route::get('/posts/stats', [AdminController::class, 'postsStats']);
        Route::get('/posts', [AdminController::class, 'getPosts']);
        Route::post('/posts', [AdminController::class, 'storePost']);

        // Settings API
        Route::post('/settings', [AdminController::class, 'updateSettings']);
        Route::post('/settings/security', [AdminController::class, 'updateSecuritySettings']);
        Route::post('/settings/appearance', [AdminController::class, 'updateAppearanceSettings']);

        // System API
        Route::post('/system/clear-cache', [AdminController::class, 'clearCache']);
        Route::post('/system/optimize', [AdminController::class, 'optimizeSystem']);
        Route::post('/admins', [\App\Http\Controllers\AdminController::class, 'storeAdmin']);
        Route::get('/admins', [\App\Http\Controllers\AdminController::class, 'getAdmins']);
        Route::delete('/admins/{id}', [\App\Http\Controllers\AdminController::class, 'deleteAdmin']);
    });
});
