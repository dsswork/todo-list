<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Task\FilterRequest;
use App\Http\Requests\Api\Task\StoreRequest;
use App\Http\Requests\Api\Task\UpdateRequest;
use App\Models\Task;
use App\Services\Api\ApiAnswerService;
use App\Services\Api\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function __construct(protected TaskService $taskService)
    {
    }

    public function index(FilterRequest $request): JsonResponse
    {
        $tasks = $this->taskService->getFilteredTasks($request->validated());
        return ApiAnswerService::successfulAnswerWithData($tasks);
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $task = $this->taskService->createNewTask($request->validated());
        return ApiAnswerService::successfulAnswerWithData($task);
    }

    public function show(Task $task): JsonResponse
    {
        $task = $this->taskService->getTaskWithSubtasks($task);
        return ApiAnswerService::successfulAnswerWithData($task);
    }

    public function update(UpdateRequest $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);
        $task = $this->taskService->updateTask($task, $request->validated());
        return ApiAnswerService::successfulAnswerWithData($task);
    }

    public function destroy(Task $task): JsonResponse
    {
        $response = Gate::inspect('delete', $task);

        if ($response->allowed()) {
            $task = $this->taskService->deleteTask($task);
            return ApiAnswerService::successfulAnswerWithData($task);
        } else {
            return ApiAnswerService::errorAnswer('You have not rights', 403);
        }

    }

    public function complete(Task $task): JsonResponse
    {
        $this->authorize('update', $task);
        $task = $this->taskService->completeTask($task);

        return ApiAnswerService::successfulAnswerWithData($task);
    }
}
