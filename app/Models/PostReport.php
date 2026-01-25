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

    /**
     * Get the post that was reported
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }

    /**
     * Get the student who reported
     */
    public function reporter()
    {
        return $this->belongsTo(Student::class, 'reported_by', 'student_id');
    }
}
