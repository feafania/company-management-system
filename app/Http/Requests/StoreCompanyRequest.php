<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['nullable', 'email', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048', 'dimensions:min_width=100,min_height=100'],
            'website' => ['nullable', 'url:http,https', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'    => 'Please enter the company name.',
            'name.max'         => 'The company name may not be longer than 255 characters.',
            'email.email'      => 'Please enter a valid email address.',
            'logo.image'       => 'The logo must be an image file.',
            'logo.dimensions'  => 'The logo must be at least 100×100 pixels.',
            'logo.max'         => 'The logo may not be larger than 2 MB.',
            'logo.uploaded'    => 'The logo failed to upload. Please try a smaller file.',
            'website.url'      => 'Please enter a valid URL, for example https://example.com.',
        ];
    }
}
