<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class QuizzeRequest extends FormRequest
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
            'name'                     => 'required|unique:quizzes,name|string',
            'section_id'               => 'required|exists:sections,id',
            'subject_id'               => 'required|exists:subjects,id',
            'max_score'                => 'nullable|numeric|min:10',

            'questions'                => 'nullable|array|min:1',
            'questions.*.title'        => 'required|string|max:500',
            'questions.*.answers'      => 'required|array|min:2|max:4',
            'questions.*.answers.*'    => 'required|string|max:255',
            'questions.*.right_answer' => 'required|string|max:255',
            'questions.*.score'        => 'required|integer|min:1|max:100',
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
