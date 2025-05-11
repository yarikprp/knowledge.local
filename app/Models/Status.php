<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Attempt;

class Status extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function attempts()
    {
        return $this->hasMany(Attempt::class);
    }
}
