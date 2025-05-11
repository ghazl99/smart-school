<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SectionRequest extends FormRequest
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
            'Name' => $this->isMethod('post') ? 'required|string|max:255|unique:sections,Name': 'nullable|string|max:255|unique:sections,Name,' . $this->id,
            'Classroom_id' => $this->isMethod('post') ? 'required|exists:classrooms,id':'nullable|exists:classrooms,id',
            'max_count' => $this->isMethod('post') ? 'required|integer|min:1' : 'nullable|integer|min:1',
            'Status' =>'nullable|boolean'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors(),
        ], 422));
    }
}
