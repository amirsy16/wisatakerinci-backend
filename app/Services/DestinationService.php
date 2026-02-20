<?php

namespace App\Services;

use App\Models\Destination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DestinationService
{
    /**
     * Get all active destinations with optional filters.
     *
     * Supports:
     *   - ?search=keyword  → searches name and description
     *   - ?category=slug   → filters by category slug
     *   - ?per_page=15     → pagination size (default 12)
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $query = Destination::query()
            ->where('status', 'active')
            ->with(['images' => fn ($q) => $q->where('is_primary', true), 'categories'])
            ->withAvg(['reviews' => fn ($q) => $q->whereNotNull('approved_at')], 'rating')
            ->withCount(['reviews' => fn ($q) => $q->whereNotNull('approved_at')]);

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['category'])) {
            $query->whereHas('categories', fn ($q) => $q->where('slug', $filters['category']));
        }

        $perPage = isset($filters['per_page']) ? (int) $filters['per_page'] : 12;

        return $query->latest()->paginate($perPage);
    }

    /**
     * Get a single destination by slug with all relations loaded.
     */
    public function getBySlug(string $slug): ?Destination
    {
        return Destination::where('slug', $slug)
            ->where('status', 'active')
            ->with([
                'images',
                'categories',
                'approvedReviews.user',
            ])
            ->firstOrFail();
    }
}
