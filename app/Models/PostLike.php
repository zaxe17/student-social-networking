<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostLike extends Model
{
    protected $table = 'post_likes';
    protected $primaryKey = 'like_id';
    protected $fillable = ['post_id', 'student_id'];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }
}