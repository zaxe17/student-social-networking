<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    protected $table = 'post_categories';
    protected $primaryKey = 'category_id';
    public $timestamps = false;
    protected $fillable = ['category_name'];

    public function posts()
    {
        return $this->hasMany(Post::class, 'category_id', 'category_id');
    }
}
