<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'priority',
        'title',
        'description',
        'status',
        'completed_at',
        'user_id',
        'task_id'
    ];

    protected $casts = [
        'completed_at' => 'date',
        'status' => TaskStatus::class
    ];

    public static function findToDoTask(Collection $tasks): bool
    {
        foreach ($tasks as $task) {
            if($task->tasks) {
                if(self::findToDoTask($task->tasks)) {
                    return true;
                };
            }
            if($task->status->value==TaskStatus::TODO->value) {
                return true;
            }
        }

        return false;
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class)->with('tasks');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
