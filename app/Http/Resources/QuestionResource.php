<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'section_id'       =>$this->title,
            'name'             =>$this->answers,
            'right_answer'     =>$this->right_answer,
            'score'            =>$this->score,
            // 'subject with question'  =>$this->assignTeachers->map(function ($assign) {
            //         return [
            //             'subject_name' => $assign->subject->Name,
            //             'questions' => $assign->quizzes->flatMap(function ($quiz) {
            //                 return $quiz->questions->map(function ($q) {
            //                     return [
            //                         'id' => $q->id,
            //                         'title' => $q->title,
            //                         'answers' => $q->answers,
            //                         'right_answer' => $q->right_answer,
            //                         'score' => $q->score,
            //                     ];
            //                 });
            //             })->values(), // values() لتنظيف الفهارس
            //         ];
            //     })->unique('subject_name')->values()
        ];
    }
}
