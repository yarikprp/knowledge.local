<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Test;
use App\Models\QuestionOption;
use App\Models\QuestionCorrectAnswer;
use App\Models\QuestionType;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'test_id',
        'question_type_id',
        'question_text',
        'points',
        'order'
    ];

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function correctAnswers()
    {
        return $this->hasMany(QuestionCorrectAnswer::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function questionType()
    {
        return $this->belongsTo(QuestionType::class);
    }
}
