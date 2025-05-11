<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Test;
use App\Models\User;
use App\Models\Status;
use App\Models\Answer;

class Attempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'test_id',
        'total_questions',
        'passing_score',
        'started_at',
        'finish_at',
        'score',
        'is_passed',
        'status_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
