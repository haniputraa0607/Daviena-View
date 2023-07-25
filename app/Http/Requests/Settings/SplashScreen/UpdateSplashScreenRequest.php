<?php

namespace App\Http\Requests\Settings\SplashScreen;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSplashScreenRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'default_home_splash_duration' => 'required|int|max:15',
            'default_home_splash_screen' => 'nullable|mimes:png,jpg,mp4|max:5120'
        ];
    }

    public function messages()
    {
        return [
            "default_home_splash_duration" => "Durasi tidak boleh lebih dari 15 detik!",
            "default_home_splash_screen.max" => "Media tidak boleh lebih dari 5MB!",
        ];
    }

    public function attributes()
    {
        return[
            'default_home_splash_duration' => 'Durasi',
            'default_home_splash_screen' => 'Media'
        ];
    }
}
