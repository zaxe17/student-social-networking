<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $table = 'posts';
    protected $primaryKey = 'post_id';

    protected $fillable = [
        'student_id',
        'content',
        'category_id',
    ];

    public function author()
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'category_id', 'category_id');
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class, 'post_id', 'post_id');
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class, 'post_id', 'post_id')->latest();
    }
}
