<?php

namespace App\Models;

class Task extends BaseModel
{
    protected $fillable = [
        'description',
        'status',
        'user_id',
        'project_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
