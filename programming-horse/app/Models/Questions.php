<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    use HasFactory;
    protected $fillable = [
        'question_id',
        'language',
        'topic_id',
        'question',
        'correct_answer',
        'incorrect_answer_1',
        'incorrect_answer_2',
        'incorrect_answer_3',
        'validated',
    ];

}
