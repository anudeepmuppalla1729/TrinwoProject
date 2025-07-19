<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Show the user dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $questionsCount = $user->questions()->count();
        $answersCount = $user->answers()->count();
        $postsCount = $user->posts()->count();
        
        // Calculate total upvotes from answer_votes and post_votes tables
        $answerUpvotes = \App\Models\AnswerVote::whereHas('answer', function($query) use ($user) {
            $query->where('user_id', $user->user_id);
        })->where('vote_type', 'upvote')->count();
        
        $postUpvotes = \App\Models\PostVote::whereHas('post', function($query) use ($user) {
            $query->where('user_id', $user->user_id);
        })->where('vote_type', 'upvote')->count();
        
        $totalUpvotes = $answerUpvotes + $postUpvotes;

        // Recent activity: last 3 questions, answers, and posts
        $recentQuestions = $user->questions()->latest()->take(1)->get();
        $recentAnswers = $user->answers()->latest()->take(1)->get();
        $recentPosts = $user->posts()->latest()->take(1)->get();

        // Merge and sort by created_at descending
        $recentActivity = collect([])
            ->merge($recentQuestions)
            ->merge($recentAnswers)
            ->merge($recentPosts)
            ->sortByDesc('created_at')
            ->take(3);

        return view('pages.profile.dashboard', [
            'questionsCount' => $questionsCount,
            'answersCount' => $answersCount,
            'postsCount' => $postsCount,
            'totalUpvotes' => $totalUpvotes,
            'recentActivity' => $recentActivity,
        ]);
    }

    /**
     * Show the user's answers.
     */
    public function answers()
    {
        $user = Auth::user();
        $answers = $user->answers()->with('question')->latest()->get();
        return view('pages.profile.answers', [
            'answers' => $answers
        ]);
    }

    /**
     * Show the user's questions.
     */
    public function questions()
    {
        $userId = Auth::id();
        
        // Get user's questions
        $questions = \App\Models\Question::where('user_id', $userId)
            ->with(['answers', 'tags'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($question) {
                return [
                    'id' => $question->question_id,
                    'title' => $question->title,
                    'description' => $question->description,
                    'created_at' => $question->created_at->format('M d, Y'),
                    'answers' => $question->answers->count(),
                    'upvotes' => 0, // Questions don't have upvotes in this system
                    'downvotes' => 0, // Questions don't have downvotes in this system
                    'tags' => $question->tags->pluck('name')->toArray()
                ];
            });
        
        return view('pages.profile.questions', compact('questions'));
    }

    /**
     * Show the user's posts.
     */
    public function posts()
    {
        $user = Auth::user();
        $posts = $user->posts()->with('comments')->latest()->get();
        return view('pages.profile.posts', [
            'posts' => $posts
        ]);
    }

    /**
     * Show the user's followers.
     */
    public function followers()
    {
        $user = Auth::user();
        $followers = $user->followers()->with('follower')->get();
        return view('pages.profile.followers', [
            'followers' => $followers
        ]);
    }

    /**
     * Show the user's following.
     */
    public function following()
    {
        $user = Auth::user();
        $following = $user->following()->with('user')->get();
        return view('pages.profile.following', [
            'following' => $following
        ]);
    }

    /**
     * Show the user's bookmarks.
     */
    public function bookmarks()
    {
        $userId = Auth::id();
        // Get user's bookmarked posts
        $bookmarkedPosts = \App\Models\Post::whereHas('bookmarks', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with(['user', 'images', 'comments'])
          ->orderBy('created_at', 'desc')
          ->get();
        // Get user's bookmarked questions
        $bookmarkedQuestions = \App\Models\Question::whereHas('bookmarks', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with(['user', 'answers'])
          ->orderBy('created_at', 'desc')
          ->get();
        return view('pages.profile.bookmarks', [
            'bookmarkedPosts' => $bookmarkedPosts,
            'bookmarkedQuestions' => $bookmarkedQuestions
        ]);
    }

    /**
     * Show the user's settings page.
     */
    public function settings()
    {
        $user = Auth::user();
        return view('pages.profile.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:100',
            'phone' => 'nullable|string|max:15',
            'age' => 'nullable|integer|min:1|max:120',
            'gender' => 'nullable|in:Male,Female,Other',
            'studying_in' => 'nullable|string|max:150',
            'expert_in' => 'nullable|string|max:150',
            'interests' => 'nullable|string',
            'bio' => 'nullable|string',
            'profile_pic' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('profile_pic')) {
            $path = $request->file('profile_pic')->store('profile_pics', 'public');
            $validated['profile_pic'] = $path;
        }
        // Only update interests if present in the request
        if (!$request->has('interests')) {
            unset($validated['interests']);
        }
        try {
            $user->update($validated);
            return redirect()->back()->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating your profile.');
        }
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);
        $user = Auth::user();
        if (!\Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }
        $user->password = bcrypt($request->password);
        $user->save();
        return redirect()->back()->with('success', 'Password updated successfully!');
    }
}
