<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexProjectRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'limit' => ['nullable', 'integer', 'min:1'],
        ];
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
