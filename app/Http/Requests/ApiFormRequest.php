<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Str;

abstract class ApiFormRequest extends FormRequest
{
    public function withValidator(Validator $validator): void
    {
        $allowedFields = array_map(
            static fn (string $field): string => explode('.', $field, 2)[0],
            array_keys($this->rules()),
        );

        $validator->after(function (Validator $validator) use ($allowedFields): void {
            foreach (array_diff(array_keys($this->all()), $allowedFields) as $field) {
                $validator->errors()->add($field, 'Field tidak dikenal.');
            }
        });
    }

    protected function failedValidation(Validator $validator): void
    {
        $details = [];
        foreach ($validator->errors()->messages() as $field => $messages) {
            foreach ($messages as $message) {
                $details[] = ['field' => $field, 'message' => $message];
            }
        }

        throw new HttpResponseException(response()->json([
            'success' => false,
            'error' => [
                'code' => 'VALIDATION_ERROR',
                'message' => 'Data tidak valid.',
                'details' => $details,
            ],
            'request_id' => (string) Str::uuid(),
        ], 422));
    }
}
