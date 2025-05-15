<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id'                        => $this->id,
            'Name'                      => $this->Name,
            'ClassroomName'             => $this->classroom->Name,
            'count'                     => $this->count,
            'maxCount'                  => $this->max_count,

            'teachers with subject'     => $this->whenLoaded('assignTeachers', function () {
                                            return $this->assignTeachers
                                                ->groupBy('teacher_id')
                                                ->map(fn($section) => [
                                                    'teacher' => new TeacherResource($section->first()->teacher),
                                                    'subjects' => ModelResource::collection(
                                                        $section->pluck('subject')->unique('id')
                                                    ),
                                                ])->values();
                                        }),
        ];
    }
}
