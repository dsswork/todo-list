<?php

namespace App\Http\Resources;

use App\Enums\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $subtasks = TaskResource::collection($this->tasks);

        $result = [
            'id' => $this->id,
            "status" => $this->status->name(),
            "priority" => $this->priority,
            "title" => $this->title,
            "description" => $this->description,
            "created_at" => $this->created_at->format('d.m.Y'),
        ];

        if($this->completed_at) {
            $result+=['completed_at' => $this->completed_at->format('d.m.Y')];
        }

        if(count($subtasks)) {
            $result+=['subtasks' => $subtasks];
        }

        return $result;
    }
}
