<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostComment extends Model
{
    use SoftDeletes;

    protected $table = 'post_comments';
    protected $primaryKey = 'comment_id';

    protected $fillable = [
        'post_id',
        'student_id',
        'content',
    ];

    public function author()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    /* public function likes()
    {
        return $this->hasMany(PostCommentLike::class, 'comment_id', 'comment_id');
    } */
}
