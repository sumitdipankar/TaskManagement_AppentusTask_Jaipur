<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'status',
        'due_date',
    ];

    public function getUsers()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getCategory()
    {
        return $this->hasOne(Category::class,'id','category_id');
    }
}
