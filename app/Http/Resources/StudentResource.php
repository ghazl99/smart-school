<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'profile_photo_url' => $this->user->profile_photo_url,
            'gender' => $this->gender?->Name,
            'nationality' => $this->nationality?->Name,
            'blood' => $this->blood?->Name,
            'date_of_birth' => $this->Date_Birth,
            'section' => $this->section?->Name,
            'parent_name' => ['Name_Father'=>$this->parent?->Name_Father,'Name_Mother'=>$this->parent?->Name_Mother],
            'academic_year' => $this->academic_year,
        ];
    }
}
