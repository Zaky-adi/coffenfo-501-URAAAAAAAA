<?php

namespace App\Http\Requests;

class UpdatePasswordRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string', 'max:128'],
            'new_password' => ['required', 'string', 'min:8', 'max:128', 'different:current_password'],
        ];
    }
}
