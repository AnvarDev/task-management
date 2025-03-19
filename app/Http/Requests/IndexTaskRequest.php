<?php

namespace App\Http\Requests;

use App\Models\Project;

class IndexTaskRequest extends UpdatePriorityTaskRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'project_id' => ['nullable', 'exists:' . (new Project)->getTable() . ',' . (new Project)->getKeyName()],
            'limit' => ['nullable', 'integer', 'min:1'],
        ]);
    }

    /**
     * Get the project_id parameter of the request.
     *
     * @return int|null
     */
    public function getProjectId(): ?int
    {
        return $this->input('project_id');
    }

    /**
     * Get the limit parameter of the request.
     *
     * @return int
     */
    public function getLimit(): int
    {
        return intval(!is_null($this->input('limit')) ? $this->input('limit') : 20);
    }
}
