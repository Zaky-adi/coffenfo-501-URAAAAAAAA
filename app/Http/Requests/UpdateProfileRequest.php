<?php

namespace App\Http\Requests;

class UpdateProfileRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('full_name'))) {
            $this->merge(['full_name' => trim($this->input('full_name'))]);
        }
        if (is_string($this->input('username'))) {
            $this->merge(['username' => strtolower(trim($this->input('username')))]);
        }
    }

    public function rules(): array
    {
        return [
            'full_name' => ['sometimes', 'required', 'string', 'min:2', 'max:120'],
            'username' => ['sometimes', 'nullable', 'string', 'min:3', 'max:40', 'regex:/^[a-z0-9_.]+$/', 'unique:users,username,' . $this->user()->id],
            'photo_upload_id' => ['sometimes', 'nullable', 'uuid'],
        ];
    }
}
