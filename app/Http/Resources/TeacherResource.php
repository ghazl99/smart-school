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
            'id'                => $this->user->id,
            'name'              => $this->user->name,
            'email'             => $this->user->email,
            'profile_photo_url' => $this->user->profile_photo_url,
            'Joining_Date'      =>$this->Joining_Date,
            'Address'           =>$this->Address,
            'gender'            => $this->gender?->Name,
            'specialization'    =>$this->specialization?->Name,
            'sections'          =>$this->sections?->pluck('Name')
        ];
    }
}
