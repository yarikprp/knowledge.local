<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Calendar;

class EventType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function calendars()
    {
        return $this->hasMany(Calendar::class);
    }
}
