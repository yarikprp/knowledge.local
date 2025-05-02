<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{
    use HasFactory;

    protected $fillable = ['subject_id', 'name', 'content'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
