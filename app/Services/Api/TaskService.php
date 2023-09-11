<?php

namespace App\Services\Api;

use App\Enums\TaskStatus;
use App\Http\Resources\TaskResource;
use App\Models\Task;

class TaskService
{
    public function getFilteredTasks(array $parameters): array
    {
        $userId = auth()->id();
        $tasks = Task::where('user_id', $userId)
            ->when($parameters['status'] ?? null, function ($query) use ($parameters) {
                $query->where('status', $parameters['status']);
            })
            ->when($parameters['priority']['from'] ?? null, function ($query) use ($parameters) {
                $query->where('priority', '>=', $parameters['priority']['from']);
            })
            ->when($parameters['priority']['to'] ?? null, function ($query) use ($parameters) {
                $query->where('priority', '<=', $parameters['priority']['to']);
            })
            ->when($parameters['sort'] ?? null, function ($query) use ($parameters) {
                foreach ($parameters['sort'] as $key => $direction) {
                    $query->orderBy($key, $direction);
                }
            })
            ->when($parameters['search']['title'] ?? null, function ($query) use ($parameters) {
                $query->where('title', 'like', '%'.$parameters['search']['title'].'%');
            })
            ->whereNull('task_id')->with('tasks')->get();

        return TaskResource::collection($tasks)->toArray(request());
    }

    public function createNewTask(array $fields): array
    {
        $task = Task::create($fields);
        $result = TaskResource::make($task->refresh())->toArray(request());
        return $result;
    }

    public function getTaskWithSubtasks(Task $task): array
    {
        $task->load('tasks');
        return TaskResource::make($task)->toArray(request());
    }

    public function updateTask(Task $task, array $fields): array
    {
        $task->update($fields);
        return TaskResource::make($task)->toArray(request());
    }

    public function completeTask(Task $task): array
    {
        $task->update([
            'status' => TaskStatus::DONE->value,
            'completed_at' => now()
        ]);

        return TaskResource::make($task)->toArray(request());
    }

    public function deleteTask(Task $task): array
    {
        $task->delete();
        return TaskResource::make($task)->toArray(request());
    }

}
