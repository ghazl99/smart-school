<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ParentRequest extends FormRequest
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
            // father
            'Name_Father' => 'required|string',
            'National_ID_Father' => 'required|string|unique:my_parents,National_ID_Father|size:11',
            'Passport_ID_Father' => 'nullable|string|unique:my_parents,Passport_ID_Father',
            'Phone_Father' => 'required|string|unique:my_parents,Phone_Father',
            'Job_Father' => 'required|string',
            'Nationality_Father_id' => 'required',
            'Blood_Type_Father_id' => 'required',
            'Religion_Father_id' => 'required',
            'Address_Father' => 'required|string',
            'image_father' => 'nullable|image|max:2048',

            //mother
            'Name_Mother' => 'required|string',
            'National_ID_Mother' => 'required|string|unique:my_parents,National_ID_Mother|size:11',
            'Passport_ID_Mother' => 'nullable|string|unique:my_parents,Passport_ID_Mother',
            'Phone_Mother' => 'required|string|unique:my_parents,Phone_Mother',
            'Job_Mother' => 'required|string',
            'Nationality_Mother_id' => 'required',
            'Blood_Type_Mother_id' => 'required',
            'Religion_Mother_id' => 'required',
            'Address_Mother' => 'required|string',
            'image_Mother' => 'nullable|image|max:2048',
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
