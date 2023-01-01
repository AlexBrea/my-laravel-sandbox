<?php

namespace App\Models;

use App\Models\Project;
use App\Models\TaskTimer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'project_id',
        'limit_date',
    ];

    /**
     * Get the project that owns the task.
     */
    public function project()
    {
        return $this->belongsTo( Project::class );
    }

    /**
     * Get the timers for the tasks.
     */
    public function timers()
    {
        return $this->hasMany( TaskTimer::class );
    }

    public function getIdFromAction() {
        return  $this->toArray()['id'];
    }
}
