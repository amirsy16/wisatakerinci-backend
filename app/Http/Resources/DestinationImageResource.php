<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DestinationImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $path = $this->image_path;

        if (!$path) {
            $imageUrl = null;
        } elseif (str_starts_with($path, 'http')) {
            $imageUrl = $path;
        } else {
            $imageUrl = asset('storage/' . $path);
        }

        return [
            'id'         => $this->id,
            'image_url'  => $imageUrl,
            'is_primary' => $this->is_primary,
        ];
    }
}
