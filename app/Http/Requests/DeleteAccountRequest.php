<?php

namespace App\Http\Requests;

class DeleteAccountRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['password' => ['required', 'string', 'max:128']];
    }
}
