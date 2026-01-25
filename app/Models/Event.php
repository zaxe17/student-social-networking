<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'location',
        'registration_url',
        'header_path',
    ];

    protected $casts = [
        'event_date' => 'date',
    ];
}
