<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParentResource extends JsonResource
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
            'father'            => [
                'Name_Father'        => $this->Name_Father ?? null,
                'National_ID_Father' => $this->National_ID_Father ?? null,
                'Phone_Father'       => $this->Phone_Father ?? null,
                'Job_Father'         => $this->Job_Father ?? null,
                'Address_Father'     => $this->Address_Father ?? null,

            ],
            'mother'            => [
                'Name_Mother'        => $this->Name_Mother ?? null,
                'Phone_Mother'       => $this->Phone_Mother ?? null,
                'Job_Mother'         => $this->Job_Mother ?? null,
                'Address_Mother'     => $this->Address_Mother ?? null,
            ]
        ];
    }
}
