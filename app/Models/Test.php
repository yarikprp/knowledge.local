<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TestType;
use App\Models\QuestionType;
use App\Models\Calendar;
use App\Models\Attempt;
use App\Models\Question;
use App\Models\results;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'test_types_id',
        'start_date',
        'end_date',
        'attempt_limit',
        'time_limit',
        'is_active',
    ];

    public function testType()
    {
        return $this->belongsTo(TestType::class, 'test_types_id');
    }

    public function questionType()
    {
        return $this->belongsTo(QuestionType::class);
    }

    public function calendars()
    {
        return $this->hasMany(Calendar::class);
    }

    public function attempts()
    {
        return $this->hasMany(Attempt::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }
}
