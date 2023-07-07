<?php

namespace App\Http\Requests\Settings\AutoResponse;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAutoResponseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $emailAddresses = $this->input('forward_email');

        if ($emailAddresses) {
            $emailAddresses = array_map('trim', explode(',', $emailAddresses));
            $emailAddresses = array_filter($emailAddresses); // Remove empty values
            $this->merge([
                'forward_email' => implode(',', $emailAddresses),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            "email_toggle"          => "required|in:1,0",
            "email_subject"         => "required_if:email_toggle,1",
            "email_content"         => "required_if:email_toggle,1",
            "push_toggle"           => "required|in:1,0",
            "push_subject"          => "required_if:push_toggle,1",
            "push_content"          => "required_if:push_toggle,1",
            "push_click_to"         => "required_if:push_toggle,1",
            "push_image"            => "nullable|mimes:png,jpg|max:5120",
            "forward_toggle"        => "required|in:1,0",
            "forward_email"         => "required_if:forward_toggle,1",
            "forward_email_subject" => "required_if:forward_toggle,1",
            "forward_email_content" => "required_if:forward_toggle,1",
        ];
    }

    public function messages()
    {
        return [
            'email_subject.required_if'         => 'The email subject is required when email toggle is enabled.',
            'email_content.required_if'         => 'The email content is required when email toggle is enabled.',
            "push_subject.required_if"          => "The push notification subject is required when push notification toggle is enabled.",
            "push_content.required_if"          => "The push notification content is required when push notification toggle is enabled.",
            "push_click_to.required_if"         => "The push notification click action is required when push notification toggle is enabled.",
            "forward_email.required_if"         => "The forward email address is required when forward email toggle is enabled",
            "forward_email_subject.required_if" => "The forward email subject is required when forward email toggle is enabled",
            "forward_email_content.required_if" => "The forward email content is required when forward email toggle is enabled.",
        ];
    }
}
