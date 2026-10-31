<?php

namespace App\Http\Requests;

class LoginRequest extends ApiFormRequest
{
    protected function prepareForValidation(): void
    {
        $identifier = $this->input('identifier');
        $device = $this->input('device');

        $this->merge([
            'identifier' => is_string($identifier) ? mb_strtolower(trim($identifier)) : $identifier,
            'device' => is_array($device) ? array_map(
                static fn ($value) => is_string($value) ? trim($value) : $value,
                $device,
            ) : $device,
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'identifier' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
            'device' => ['sometimes', 'array:device_id,device_name,platform,app_version'],
            'device.device_id' => ['required_with:device', 'string', 'max:64'],
            'device.device_name' => ['nullable', 'string', 'max:100'],
            'device.platform' => ['required_with:device', 'in:android,ios'],
            'device.app_version' => ['required_with:device', 'string', 'max:20'],
        ];
    }
}
