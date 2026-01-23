<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'game_id',
        'completed',
        'score',
        'duration_seconds',
        'started_at',
        'ended_at'
    ];

    /**
     * Get the user that owns the game session.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the game that owns the game session.
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}