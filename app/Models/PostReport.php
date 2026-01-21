<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostReport extends Model
{
    protected $table = 'post_reports';

    protected $fillable = [
        'post_id',
        'reported_by',
        'reason',
        'details',
    ];
}
