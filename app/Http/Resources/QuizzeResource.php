<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizzeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                =>$this->id,
            'name'              =>$this->name,
            'max_score'         => $this->when(!is_null($this->max_score), $this->max_score),
            'teacher_name'      =>$this->assignTeacher->teacher->user->name,
            'subject_name'      =>$this->assignTeacher->subject->Name,
            'section_name'      =>$this->assignTeacher->section->Name,
            'marks'             =>MarkResource::collection($this->whenLoaded('marks')),
            'questions'         =>QuestionResource::collection($this->whenLoaded('questions'))
        ];
    }
}
