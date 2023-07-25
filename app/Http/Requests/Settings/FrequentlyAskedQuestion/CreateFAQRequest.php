<?php

namespace App\Http\Requests\Settings\FrequentlyAskedQuestion;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateFAQRequest extends FormRequest
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
            'question' => 'required|string|max:255',
            'header' => 'required|string|max:100',
            'type' => 'required|string|in:step_by_step,regular,webview',
            'answer_header' => Rule::when($this->type == 'step_by_step', 'required|string|max:100', 'prohibited'),
            'answer' => Rule::when($this->type != 'step_by_step', 'required|string', 'prohibited'),
            'answer_title' => Rule::when($this->type == 'step_by_step', 'required|array|min:1', 'prohibited'),
            'answer_title.*' => Rule::when($this->type == 'step_by_step', 'required|string|max:255', 'prohibited'),
            'answer_description' => Rule::when($this->type == 'step_by_step', 'required|array|min:1', 'prohibited'),
            'answer_description.*' => Rule::when($this->type == 'step_by_step', 'required|string|max:255', 'prohibited'),
            'file' => Rule::when($this->type == 'step_by_step', 'required|array|min:1', 'prohibited'),
            'file.*' => Rule::when($this->type == 'step_by_step', 'required|mimes:png,jpg|max:5120', 'prohibited'),
        ];
    }

    public function attributes()
    {
        return[
            'type' => 'Answer Type',
            'header' => 'Header Text',
            'answer_header' => 'Answer Header',
            'answer_title' => 'Answer Title',
            'answer_description' => 'Answer Description',
            'file' => 'Answer Image',
            'answer_title.*' => 'Answer: Step Title',
            'answer_description.*' => 'Answer: Step Description',
            'file.*' => 'Answer: Step Image',
        ];
    }
}
