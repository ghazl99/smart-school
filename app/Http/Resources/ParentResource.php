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
        $image = $this->getFirstMedia('users');
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'image' => $image ? url('storage/' . $image->id . '/' . $image->file_name) : null,
            'father' => [
                'Name_Father' => $this->parent->Name_Father ?? null,
                'National_ID_Father' => $this->parent->National_ID_Father ?? null,
                'Phone_Father' => $this->parent->Phone_Father ?? null,
                'Job_Father' => $this->parent->Job_Father ?? null,
                'Address_Father' => $this->parent->Address_Father ?? null,

            ],
            'mother' => [
                'Name_Mother' => $this->parent->Name_Mother ?? null,
                'Phone_Mother' => $this->parent->Phone_Mother ?? null,
                'Job_Mother' => $this->parent->Job_Mother ?? null,
                'Address_Mother' => $this->parent->Address_Mother ?? null,
            ]
        ];
    }
}
