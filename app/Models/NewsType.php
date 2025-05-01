<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NewsType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function news()
    {
        return $this->hasMany(News::class);
    }

    public function hasRoleById($roleId)
    {
        return $this->roles->contains('id', $roleId);
    }
}
