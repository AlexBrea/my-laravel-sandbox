<?php

namespace App\Models;

use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskTimer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'task_id',
        'total_time',
        'task_start',
        'task_end',
    ];

    /**
     * Get the task that owns the timer.
     */
    public function task()
    {
        return $this->belongsTo( Task::class );
    }

}
