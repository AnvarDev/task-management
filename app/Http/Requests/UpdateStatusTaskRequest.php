<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'in:' . implode(',', array_keys(config('tasks.status')))],
        ];
    }

    /**
     * Get the status parameter of the request.
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->input('status');
    }
}
