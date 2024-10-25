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

}
