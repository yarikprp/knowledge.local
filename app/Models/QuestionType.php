<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Question;

class QuestionType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
