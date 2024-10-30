<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
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

    public function rounds()
    {
        return $this->hasMany(Round::class, 'question_id');
    }

}
