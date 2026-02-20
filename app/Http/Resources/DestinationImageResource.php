<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class DestinationImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Jika image_path adalah URL eksternal, gunakan langsung
        if (str_starts_with($this->image_path, 'http')) {
            $imageUrl = $this->image_path;
        }
        // Jika file ada di storage lokal, gunakan asset URL
        elseif (Storage::disk('public')->exists($this->image_path)) {
            $imageUrl = asset('storage/' . $this->image_path);
        }
        // Fallback: placeholder dari picsum.photos berdasarkan ID
        else {
            $seed = $this->id ?? 1;
            $imageUrl = "https://picsum.photos/seed/{$seed}/800/600";
        }

        return [
            'id'         => $this->id,
            'image_url'  => $imageUrl,
            'is_primary' => $this->is_primary,
        ];
    }
}
