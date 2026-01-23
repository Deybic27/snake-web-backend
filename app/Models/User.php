<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'score'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**
     * Get the game sessions for the user.
     */
    public function gameSessions()
    {
        return $this->hasMany(GameSessions::class);
    }

    /**
     * Get the games for the user.
     */
    public function games()
    {
        return $this->hasManyThrough(
            Game::class, // Final model
            GameSessions::class, // Intermediate model
            'user_id', // Foreign key on GameSessions table
            'id', // Foreign key on Games table
            'id', // Local key on Users table
            'game_id' // Local key on GameSessions table
        );
    }
}
