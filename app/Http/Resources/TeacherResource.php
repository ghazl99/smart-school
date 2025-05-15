<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                     =>$this->id,
            'user_id'                => $this->user->id,
            'name'                   => $this->user->name,
            'email'                  => $this->user->email,
            'profile_photo_url'      => $this->user->profile_photo_url,
            'Joining_Date'           => $this->Joining_Date,
            'Address'                => $this->Address,
            'gender'                 => $this->gender?->Name,
            'specialization'         => $this->specialization?->Name,
            'sections with subject'  => $this->whenLoaded('assignTeachers', function () {
                                        return $this->assignTeachers
                                            ->groupBy('section_id')
                                            ->map(fn($teachers) => [
                                                'section' => new SectionResource($teachers->first()->section),
                                                'subjects' => ModelResource::collection(
                                                    $teachers->pluck('subject')->unique('id')
                                                ),
                                            ])->values();
                                    }),
        ];
    }
}
