<?php

namespace App\Http\Requests;

class VerifyEmailRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => ['required', 'string', 'min:32', 'max:100'],
        ];
    }
}
