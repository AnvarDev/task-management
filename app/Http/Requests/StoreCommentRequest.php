<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
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
            'text' => ['required', 'string', 'min:3', 'max:255'],
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
     * Get the text parameter of the request.
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->input('text');
    }
}
