<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\NewsType;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'content',
        'user_id',
        'news_type_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(NewsType::class, 'news_type_id');
    }
}
