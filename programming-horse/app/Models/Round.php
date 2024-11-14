<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;

class Round extends Model
{
    use HasFactory, HasCompositeKey;

    protected $primaryKey = ['game_id', 'round_id'];
    
    public $incrementing = false;  // Required for composite keys
    protected $keyType = 'int';    // Define key type if necessary


    protected $fillable = [
        'round_id',
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
