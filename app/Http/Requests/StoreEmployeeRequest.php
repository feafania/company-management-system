<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'email'      => ['nullable', 'email', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:20', 'regex:/^\+?[0-9\s\-()]+$/'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'Please enter the first name.',
            'last_name.required'  => 'Please enter the last name.',
            'company_id.required' => 'Please select a company.',
            'company_id.exists'   => 'The selected company does not exist.',
            'email.email'         => 'Please enter a valid email address.',
            'phone.max'           => 'The phone number may not be longer than 20 characters.',
            'phone.regex'         => 'The phone number may contain only digits, spaces, +, -, and parentheses.',
        ];
    }
}
