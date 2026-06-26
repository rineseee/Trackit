<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePreferencesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'theme' => 'required|in:light,dark',
            'language' => 'required|in:en,sq,it',
            'timezone' => 'required|timezone',
        ];
    }

    public function messages(): array
    {
        return [
            'theme.required' => 'Theme is required',
            'theme.in' => 'Invalid theme selected',
            'language.required' => 'Language is required',
            'language.in' => 'Invalid language selected',
            'timezone.required' => 'Timezone is required',
            'timezone.timezone' => 'Invalid timezone selected',
        ];
    }
}
