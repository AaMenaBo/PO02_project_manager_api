<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;

class Task extends BaseModel
{
    private const STATUS_TABLE = [
        'pending' => 1,
        'completed' => 2,
        'in-progress' => 3,
    ];
    // The table name
    protected $fillable = [
        'name',
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

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => array_flip(self::STATUS_TABLE)[$value] ?? null,
            set: fn ($value) => self::STATUS_TABLE[$value] ?? null,
        );
    }
}
