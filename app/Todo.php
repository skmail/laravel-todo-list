<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = ['title','status','parent_id'];

    public function parent()
    {
        return $this->belongsTo(Todo::class,'parent_id');
    }

    public function items()
    {
        return $this->hasMany(Todo::class,'parent_id');
    }

    public function assignees()
    {
        return $this->belongsToMany(User::class);
    }
}
