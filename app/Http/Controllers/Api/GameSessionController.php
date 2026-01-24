<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GameSession;
use Carbon\Carbon;
use App\Http\Controllers\UserStatController;

class GameSessionController extends Controller
{
    public function start(Request $request)
    {
        $request->validate([
            'game_id' => 'required|integer|min:1|exists:games,id',
        ], 
        [
            'game_id.exists' => 'Game not found.',
        ]);

        $gameSession = GameSession::create([
            'user_id' => $request->user()->id,
            'game_id' => $request['game_id'],
        ]);
        return response()->json([
            'message' => 'Game session created successfully',
            'game_session' => $gameSession
        ], 201);
    }

    public function complete(Request $request, $id)
    {
        $gameSession = GameSession::find($id);
        if (!$gameSession) {
            return response()->json([
                'message' => 'Game session not found'
            ], 404);
        }
        if ($gameSession->completed) {
            return response()->json([
                'message' => 'Game session already completed'
            ], 400);
        }

        $request->validate([
            'started_at' => 'required|integer',
            'ended_at' => 'required|integer',
            'score' => 'required|integer|min:0',
            'duration_seconds' => 'required|integer|min:0',
        ]);

        $started_at = Carbon::createFromTimestamp($request['started_at']);
        $ended_at = Carbon::createFromTimestamp($request['ended_at']);
  
        if (!$started_at->lessThan($ended_at)) {
            return response()->json([
                'message' => 'Invalid timestamps: started_at must be less than ended_at'
            ], 422);
        }
        
        $gameSession->started_at = $started_at;
        $gameSession->ended_at = $ended_at;
        $gameSession->score = $request['score'];
        $gameSession->duration_seconds = $request['duration_seconds'];
        $gameSession->completed = 1;
        $gameSession->save();

        UserStatController::save($request, $gameSession->game_id, $gameSession->score, $gameSession->duration_seconds);

        return response()->json([
            'message' => 'Game session updated successfully',
            'game_session' => $gameSession
        ], 200);
    }
}
