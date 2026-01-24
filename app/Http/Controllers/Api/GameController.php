<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Game;

class GameController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:1|max:100|unique:games,name',
            'description' => 'nullable|string|max:500',
            'is_active' => 'required|boolean',
        ]);

        $game = Game::create([
            'name' => $request['name'],
            'description' => $request['description'],
            'is_active' => $request['is_active'],
        ]);

        return response()->json([
            'message' => 'Game created successfully',
            'game' => $game
        ], 201);
    }   
}
