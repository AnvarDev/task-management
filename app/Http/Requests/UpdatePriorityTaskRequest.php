<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePriorityTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'priority' => ['required', 'in:' . implode(',', array_keys(config('tasks.priority')))],
        ];
    }

    /**
     * Get the priority parameter of the request.
     *
     * @return int
     */
    public function getPriority(): int
    {
        return $this->input('priority');
    }
}
