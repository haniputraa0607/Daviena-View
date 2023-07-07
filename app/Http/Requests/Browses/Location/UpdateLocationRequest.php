<?php

namespace App\Http\Requests\Browses\Location;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLocationRequest extends FormRequest
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
            "id" => "required|string",
            "status" => "nullable|string|in:1,0",
            "name" => "required|string|max:255",
            "description" => "required|string|max:500",
            "address" => "required|string|max:700",
            "building_code" => "required|string|max:3|min:3",
            "location_type" => "required|string|in:RA,FM,BW,CP",
            "developer_id" => "required|string",
            "time_start" => "required|string|regex:/^\d{2}:\d{2}$/",
            "time_end" => "required|string|regex:/^\d{2}:\d{2}$/",
            "latitude" => "required|between:-90,90",
            "longitude" => "required|between:-180,180",
            "image_id" => "nullable|array|min:1",
            "image_id.*" => "nullable|string",
            "file" => "nullable|array|min:1",
            "file.*" => "nullable|mimes:png,jpg|max:5120"
        ];
    }
}
