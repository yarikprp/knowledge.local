<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\EventType;
use App\Models\Test;

class Calendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_date',
        'event_type_id',
        'notes',
        'is_notified',
        'user_id',
        'testing_id',
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_notified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function eventType()
    {
        return $this->belongsTo(EventType::class);
    }

    public function testing()
    {
        return $this->belongsTo(Test::class);
    }
}
