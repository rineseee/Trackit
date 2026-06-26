<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'notifications_email' => 'boolean',
            'notifications_push' => 'boolean',
            'notifications_sms' => 'boolean',
            'notifications_issues' => 'boolean',
            'notifications_comments' => 'boolean',
            'notifications_mentions' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'notifications_email.boolean' => 'Email notification must be true or false',
            'notifications_push.boolean' => 'Push notification must be true or false',
            'notifications_sms.boolean' => 'SMS notification must be true or false',
        ];
    }
}
