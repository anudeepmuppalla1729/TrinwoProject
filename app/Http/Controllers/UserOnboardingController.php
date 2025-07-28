<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserOnboardingController extends Controller
{
    public function showUserInfoForm()
    {
        $user = Auth::user();
        return view('pages.user_information', compact('user'));
    }

    public function submitUserInfo(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->user_id . ',user_id|regex:/^[a-zA-Z0-9_]+$/',
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|in:Male,Female,Other',
            'studying_in' => 'required|string|max:150',
            'expert_in' => 'required|string|max:150',
        ], [
            'username.unique' => 'This username is already taken. Please choose a different one.',
            'username.regex' => 'Username can only contain letters, numbers, and underscores.',
        ]);
        $user->update($data);
        return redirect()->route('user.interests');
    }

    public function showInterestsForm()
    {
        $user = Auth::user();
        // You can pass available interests as an array if needed
        return view('pages.interests', compact('user'));
    }

    public function submitInterests(Request $request)
    {
        \Log::info('Interests submitted:', $request->all());
        $user = Auth::user();
        \Log::info('User:', ['id' => $user?->user_id, 'email' => $user?->email]);
        $interests = $request->input('interests'); // array or comma-separated
        $user->interests = is_array($interests) ? json_encode($interests) : $interests;
        $user->save();
        return redirect()->route('dashboard');
    }

    public function checkUsername(Request $request)
    {
        $username = $request->input('username');
        $user = Auth::user();
        
        $exists = User::where('username', $username)
                     ->where('user_id', '!=', $user->user_id)
                     ->exists();
        
        return response()->json(['available' => !$exists]);
    }
} 