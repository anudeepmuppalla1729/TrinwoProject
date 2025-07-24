<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ForgotPasswordController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'No user found with this email.']);
        }
        $otp = rand(100000, 999999);
        Session::put('forgot_otp_' . $request->email, $otp);
        Session::put('forgot_otp_time_' . $request->email, now());
        // Send OTP via email
        Mail::raw("Your OTP for password reset is: $otp", function ($message) use ($user) {
            $message->to($user->email)->subject('Password Reset OTP');
        });
        return response()->json(['success' => true, 'message' => 'OTP sent to your email.']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['email' => 'required|email', 'otp' => 'required']);
        $otp = Session::get('forgot_otp_' . $request->email);
        $otpTime = Session::get('forgot_otp_time_' . $request->email);
        if (!$otp || !$otpTime) {
            return response()->json(['success' => false, 'message' => 'OTP not found or expired.']);
        }
        if (now()->diffInMinutes($otpTime) > 10) {
            Session::forget('forgot_otp_' . $request->email);
            Session::forget('forgot_otp_time_' . $request->email);
            return response()->json(['success' => false, 'message' => 'OTP expired.']);
        }
        if ($request->otp != $otp) {
            return response()->json(['success' => false, 'message' => 'Invalid OTP.']);
        }
        Session::put('forgot_otp_verified_' . $request->email, true);
        return response()->json(['success' => true, 'message' => 'OTP verified.']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'new_password' => 'required|min:6',
        ]);
        if (!Session::get('forgot_otp_verified_' . $request->email)) {
            return response()->json(['success' => false, 'message' => 'OTP not verified.']);
        }
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'No user found with this email.']);
        }
        $user->password = Hash::make($request->new_password);
        $user->save();
        // Clean up session
        Session::forget('forgot_otp_' . $request->email);
        Session::forget('forgot_otp_time_' . $request->email);
        Session::forget('forgot_otp_verified_' . $request->email);
        return response()->json(['success' => true, 'message' => 'Password reset successfully. You can now log in.']);
    }
} 