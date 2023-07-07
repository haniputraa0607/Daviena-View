<?php

namespace App\Http\Requests\Browses\MobileUser;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateStatusMobileUserRequest extends FormRequest
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
            'id' => 'required|string',
            'status' => 'nullable|string|in:activate,deactivate'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'status' => false,
            'message' => $validator->errors(),
            'result' => null
        ],  422);
        throw new HttpResponseException($response);
    }
}
