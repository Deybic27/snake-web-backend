<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Get user stats
    public function getStats(Request $request)
    {
        $user = $request->user();
        $stats = $user->userStats; // Assuming a 'stats' relationship exists on the User model

        return response()->json([
            'user_id' => $user->id,
            'username' => $user->username,
            'stats' => $stats
        ], 200);
    }

}
