<?php

namespace App\Http\Requests;

class UpdatePreferencesRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bean_type_id' => ['present', 'nullable', 'integer', 'exists:bean_types,id'],
            'roast_level_id' => ['present', 'nullable', 'integer', 'exists:roast_levels,id'],
        ];
    }
}
