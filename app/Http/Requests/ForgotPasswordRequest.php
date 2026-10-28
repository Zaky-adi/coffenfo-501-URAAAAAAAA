<?php

namespace App\Http\Requests;

class ForgotPasswordRequest extends ApiFormRequest
{
    protected function prepareForValidation(): void
    {
        $email = $this->input('email');
        $this->merge([
            'email' => is_string($email) ? strtolower(trim($email)) : $email,
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
        ];
    }
}
