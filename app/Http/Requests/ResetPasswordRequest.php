<?php

namespace App\Http\Requests;

class ResetPasswordRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => ['required', 'string', 'min:32', 'max:100'],
            'new_password' => ['required', 'string', 'min:8', 'max:128'],
        ];
    }
}
