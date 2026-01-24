<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserStatController extends Controller
{
    /**
     * Save or update user stats after a game session is completed.
     */
    static function save(Request $request,int $game_id, int $score, int $duration_seconds)
    {
        $user = $request->user();
        // var_dump($user->userStats);
        $stats = $user->userStats()->where('game_id', $game_id)->first(); // Assuming a 'stats' relationship exists on the User model

        $newTotalScore = ($stats ? $stats->total_score : 0) + $score;
        $newHighScore = $stats ? max($stats->high_score, $score) : $score;
        $newTotalSessions = ($stats ? $stats->total_sessions : 0) + 1;
        $newTotalTimePlayed = ($stats ? $stats->total_time_played : 0) + $duration_seconds;

        if ($stats) {
            $stats->update([
                'total_score' => $newTotalScore,
                'high_score' => $newHighScore,
                'total_sessions' => $newTotalSessions,
                'total_time_played' => $newTotalTimePlayed
            ]);
        } else {
            $stats = $user->userStats()->create([
                'game_id' => $game_id,
                'total_score' => $newTotalScore,
                'high_score' => $newHighScore,
                'total_sessions' => $newTotalSessions,
                'total_time_played' => $newTotalTimePlayed
            ]);
        }

        return response()->json([
            'message' => 'User stats updated successfully',
            'stats' => $stats
        ], 200);
    }
}
