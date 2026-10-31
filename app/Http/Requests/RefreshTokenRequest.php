<?php

namespace App\Http\Requests;

class RefreshTokenRequest extends ApiFormRequest
{
    protected function prepareForValidation(): void
    {
        $token = $this->input('refresh_token');
        $this->merge([
            'refresh_token' => is_string($token) ? trim($token) : $token,
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $platform = strtolower((string) $this->header('X-Client-Platform', 'web'));
        $isMobile = in_array($platform, ['android', 'ios'], true);

        return [
            'refresh_token' => [$isMobile ? 'required' : 'sometimes', 'string', 'min:43', 'max:255'],
        ];
    }
}
