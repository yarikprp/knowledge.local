<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Question;
use App\Models\Attempt;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'is_correct',
        'time_spent',
        'question_id',
        'attempt_id',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function attempt()
    {
        return $this->belongsTo(Attempt::class);
    }
}
