<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'amount'       => $this->amount,
            'description'  => $this->description,
            'year'         => $this->year,
            'fee_type'     => $this->Fee_type,
            'created_at'   => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
