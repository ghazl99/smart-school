<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StudentRequest extends FormRequest
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
            'name' =>  'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'gender_id' => 'required|exists:genders,id',
            'nationalitie_id' => 'required|exists:nationalities,id',
            'blood_id' => 'required|exists:type_bloods,id',
            'Date_Birth' => 'required|date|before:today',
            'Section_id' => 'required|exists:sections,id',
            'parent_id' => 'required|exists:my_parents,id',
            'academic_year' => 'required|string|regex:/^\d{4}-\d{4}$/',
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
