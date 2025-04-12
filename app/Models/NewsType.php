<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewsType extends Model
{
    use HasFactory;


    public function news()
    {
        return $this->hasMany(News::class);
    }
}
