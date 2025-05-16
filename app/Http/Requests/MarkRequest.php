<?php

namespace App\Http\Requests;

use App\Models\Quizze;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class MarkRequest extends FormRequest
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
            'quizze_id' => 'required|exists:quizzes,id',

            'student_id'   => 'required|array',
            'student_id.*' => 'required|exists:students,id',

            'score'   => 'required|array',
            'score.*' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) {
                    preg_match('/\d+/', $attribute, $matches);
                    $index = $matches[0] ?? null;

                    $quizId = request()->quizze_id;
                    $quiz = \App\Models\Quizze::find($quizId);

                    if ($quiz && $value > $quiz->max_score) {
                        $fail("The score at index $index must not exceed the quiz's maximum score of {$quiz->max_score}.");
                    }
                },
            ],
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
