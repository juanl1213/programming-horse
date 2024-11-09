<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $primaryKey = 'question_id'; // Define custom primary key
    public $incrementing = true;           // Set to true if question_id is auto-incrementing
    protected $keyType = 'int';      
    protected $fillable = [
        'language',
        'topic_id',
        'question',
        'correct_answer',
        'incorrect_1',
        'incorrect_2',
        'incorrect_3',
        'validated',
    ];

    public function rounds()
    {
        return $this->hasMany(Round::class, 'question_id');
    }

}
