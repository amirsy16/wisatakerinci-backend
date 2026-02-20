<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DestinationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'slug'         => $this->slug,
            'description'  => $this->description,
            'location'     => $this->location,
            'map_url'      => $this->map_url,
            'ticket_price' => (float) $this->ticket_price,
            'open_hours'   => $this->open_hours,
            'status'       => $this->status,
            'categories'   => CategoryResource::collection($this->whenLoaded('categories')),
            'images'       => DestinationImageResource::collection($this->whenLoaded('images')),
            'reviews'      => ReviewResource::collection($this->whenLoaded('approvedReviews')),
            // Dari withAvg/withCount (list view)
            'rating_avg'   => $this->when(
                $this->resource->relationLoaded('approvedReviews'),
                fn () => $this->approvedReviews->isNotEmpty()
                    ? round($this->approvedReviews->avg('rating'), 1)
                    : null,
                fn () => $this->reviews_avg_rating
                    ? round((float) $this->reviews_avg_rating, 1)
                    : null,
            ),
            'review_count' => $this->when(
                isset($this->reviews_count),
                fn () => (int) $this->reviews_count,
                fn () => $this->whenLoaded('approvedReviews', fn () => $this->approvedReviews->count()),
            ),
            'created_at'   => $this->created_at->toISOString(),
        ];
    }
}
