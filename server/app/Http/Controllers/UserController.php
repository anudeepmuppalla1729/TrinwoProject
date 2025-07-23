<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Search for users by name, username, or email
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchUsers(Request $request)
    {
        $query = $request->input('query');
        
        if (empty($query)) {
            return response()->json([
                'users' => [],
            ]);
        }
        
        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('username', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->where('status', 'active')
            ->select('user_id', 'name', 'username', 'avatar')
            ->limit(10)
            ->get();
            
        return response()->json([
            'users' => $users,
        ]);
    }
}