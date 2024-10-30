<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    protected $fillable = [
        'game_id',
        'user_name',
        'language',
        'topic_id',
        'game_state',
        'game_status',
        'game_winner',
    ];

    public function rounds()
    {
        return $this->hasMany(Round::class, 'game_id');
    }
}
