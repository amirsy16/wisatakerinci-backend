<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'rating'      => $this->rating,
            'comment'     => $this->comment,
            'approved_at' => $this->approved_at?->toISOString(),
            'user'        => new UserResource($this->whenLoaded('user')),
            'destination' => $this->whenLoaded('destination', fn () => [
                'id'   => $this->destination->id,
                'name' => $this->destination->name,
                'slug' => $this->destination->slug,
            ]),
            'created_at'  => $this->created_at->toISOString(),
        ];
    }
}
