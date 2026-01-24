<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use App\Models\User;

class UserStats extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'game_id',
        'total_score',
        'high_score',
        'total_sessions',
        'total_time_played',
    ];

    /**
     * Get the user that owns the stats.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the game that owns the stats.
     */
    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
