<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    use HasFactory;

    protected $fillable = [
        'round_num',
        'game_id',
        'question_id',
        'answer_selected',
        'round_winner',
        'is_correct',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id', 'question_id');
    }
}
