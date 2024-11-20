<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyGuide extends Model
{
    use HasFactory;

    // Specify the table name if it's not the plural form of the model name
    protected $table = 'study_guides';

    // Specify the fillable fields for mass assignment
    protected $fillable = [
        'game_id',
        'user_id',
        'language',
        'topic_id',
        'incorrect_answers',
        'recommendations',
        'score',
        'created_by',
    ];

    // If you want to use timestamps, you don't need to set $timestamps = true; it's true by default
    // protected $timestamps = true;

    // Define relationships if necessary
    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_name', 'user_name'); // Assuming user_name is the primary key in users table
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}