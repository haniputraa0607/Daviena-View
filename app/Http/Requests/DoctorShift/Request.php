<?php

namespace App\Http\Requests\DoctorShift;

use App\Lib\MyHelper;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // 'user_id' => 'required|exists:users,id',
            'day' => 'required',
            'name' => 'required',
            'start' => 'required|date_format:H:i:s',
            'end'  => 'required|date_format:H:i:s',
            'price' => 'required|numeric',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'start' => Carbon::parse($this->start)->format('H:i:s'),
            'end' => Carbon::parse($this->end)->format('H:i:s'),
            'price' => MyHelper::unrupiah($this->price),
        ]);
    }
}
