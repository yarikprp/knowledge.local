<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TestType;
use App\Models\QuestionType;

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
}
