<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\StatusFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'date',
        'attachment',
        'priority',
        'status',
        'project_id',
        'user_id',
    ];

    /**
     * Get the related project.
     */
    protected function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the comments for the task.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
