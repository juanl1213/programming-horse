<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    protected $primaryKey = 'game_id'; // Define custom primary key
    public $incrementing = true;           // Set to true if question_id is auto-incrementing
    protected $keyType = 'int';   
    protected $fillable = [
        'user_id',
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
