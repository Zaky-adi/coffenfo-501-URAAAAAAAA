<?php

namespace App\Http\Requests;

class RegisterRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $email = $this->input('email');

        $this->merge([
            'full_name' => is_string($this->input('full_name')) ? trim($this->input('full_name')) : $this->input('full_name'),
            'email' => is_string($email) ? strtolower(trim($email)) : $email,
            'username' => is_string($this->input('username')) ? strtolower(trim($this->input('username'))) : $this->input('username'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => [
                'required', 'string', 'min:8', 'max:128',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (is_string($value) && (strcasecmp($value, (string) $this->input('email')) === 0
                        || strcasecmp($value, (string) $this->input('full_name')) === 0)) {
                        $fail('Kata sandi tidak boleh sama dengan email atau nama.');
                    }
                },
            ],
            'username' => ['nullable', 'string', 'min:3', 'max:40', 'unique:users,username'],
            'bean_type_id' => ['nullable', 'integer', 'exists:bean_types,id'],
            'roast_level_id' => ['nullable', 'integer', 'exists:roast_levels,id'],
            'personalization_enabled' => ['sometimes', 'boolean'],
        ];
    }

}

