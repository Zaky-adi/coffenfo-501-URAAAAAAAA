<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
                        'full_name'   => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|string|min:8',
            // 'username' field omitted because the users table does not have this column
            // optional reference data
            'bean_type_id' => 'nullable|exists:bean_types,id',
            'roast_level_id' => 'nullable|exists:roast_levels,id',
        ];
    }
}

