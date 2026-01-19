<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    protected $table = 'post_likes';
    protected $primaryKey = 'like_id';

    protected $fillable = [
        'post_id',
        'student_id',
    ];
}
