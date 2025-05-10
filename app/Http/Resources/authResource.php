<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class authResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        $image = $this->getFirstMedia('users');
        return
            ['id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'token' => $this->token,

                'image' => $image ? url('storage/' . $image->id . '/' . $image->file_name) : null,
                            ];
    }
}
