<?php

namespace App\Http\Requests\Browses\ECS;

use Illuminate\Foundation\Http\FormRequest;

class AssignECSLocationRequest extends FormRequest
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
            "id_ecs"            => "required|integer",
            "num_of_connector"  => "required|integer",
            "location_id"       => "required|integer",
        ];
    }
}
