<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OnlineClassResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'topic' => $this->topic,
            'meeting_id' => $this->meeting_id,
            'start_at' => $this->start_at,
            'duration' => $this->duration,
            'password' => $this->password,
            'start_url' => $this->start_url,
            'join_url' => $this->join_url,
            'integration' => $this->integration,

            'teacher' => [
                'id' => $this->user->id ?? null,
                'name' => $this->user->name ?? null,
                'email' => $this->user->email ?? null,
            ],

            'section' => [
                'id' => $this->section->id ?? null,
                'name' => $this->section->Name ?? null,
            ],

            'classroom' => [
                'id' => $this->section->classroom->id ?? null,
                'name' => $this->section->classroom->Name ?? null,
            ],

            'grade' => [
                'id' => $this->section->classroom->grade->id ?? null,
                'name' => $this->section->classroom->grade->Name ?? null,
            ],

            'created_at' => $this->created_at,
        ];
    }
}
