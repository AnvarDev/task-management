<?php

namespace App\Http\Resources;

use App\Http\Resources\ProjectResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * Class TaskResource.
 */
class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'attachment' => $this->when(!is_null($this->attachment), Storage::url($this->attachment)),
            'priority_name' => $this->when(!is_null($this->priority), $this->priority >= 0 ? config('tasks.priority')[$this->priority] : null),
            'status_name' => $this->when(!is_null($this->status), $this->status >= 0 ? config('tasks.status')[$this->status] : null),
            'project' => $this->when(!is_null($this->project_id), new ProjectResource($this->project)),
        ]);
    }
}
