<?php

namespace App\Models;

class Project extends BaseModel
{
    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
