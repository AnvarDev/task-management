<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    /**
     * Get the email parameter of the request.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->input('email');
    }

    /**
     * Get the password parameter of the request.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->input('password');
    }
}
