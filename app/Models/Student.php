<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    protected $table = 'students';
    protected $primaryKey = 'student_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'password_hash',
        'course',
        'year_level',
        'birthday',
        'bio',
        'facebook',
        'instagram',
        'linkedin',
        'photo',
    ];

    protected $hidden = ['password_hash'];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}
