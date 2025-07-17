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
        return view('pages.profile.dashboard');
    }

    /**
     * Show the user's answers.
     */
    public function answers()
    {
        return view('pages.profile.answers');
    }

    /**
     * Show the user's questions.
     */
    public function questions()
    {
        return view('pages.profile.questions');
    }

    /**
     * Show the user's posts.
     */
    public function posts()
    {
        return view('pages.profile.posts');
    }

    /**
     * Show the user's followers.
     */
    public function followers()
    {
        return view('pages.profile.followers');
    }

    /**
     * Show the user's following.
     */
    public function following()
    {
        return view('pages.profile.following');
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
        
        return view('pages.profile.bookmarks', [
            'bookmarkedPosts' => $bookmarkedPosts
        ]);
    }
}
