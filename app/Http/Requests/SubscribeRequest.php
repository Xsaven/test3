<?php

namespace App\Http\Requests;

use App\Models\Subscription;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string email
 * @property string ad_url
 */
class SubscribeRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'ad_url' => [
                'required',
                'url',
                'regex:/^https:\/\/www\.olx\.ua\/d\/uk\/obyavlenie\/[a-zA-Z0-9\-]+-ID[a-zA-Z0-9]+\.html$/',
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'url.regex' => 'Посилання має бути дійсним і відповідати формату OLX.',
        ];
    }
}
