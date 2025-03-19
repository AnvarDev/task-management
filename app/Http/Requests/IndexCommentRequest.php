<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;

class IndexCommentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'task_id' => ['required', 'exists:' . (new Task)->getTable() . ',' . (new Task)->getKeyName()],
            'limit' => ['nullable', 'integer', 'min:1'],
        ];
    }

    /**
     * Get the task_id parameter of the request.
     *
     * @return int
     */
    public function getTaskId(): int
    {
        return $this->input('task_id');
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
