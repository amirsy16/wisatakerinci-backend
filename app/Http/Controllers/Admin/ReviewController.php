<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ReviewController extends Controller
{
    #[OA\Get(
        path: '/api/admin/reviews',
        summary: '[Admin] Daftar semua ulasan',
        security: [['sanctum' => []]],
        tags: ['Admin - Reviews'],
        parameters: [
            new OA\Parameter(name: 'status', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['pending', 'approved']), description: 'Filter berdasarkan status'),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 1)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Berhasil',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Review')),
                        new OA\Property(property: 'meta', ref: '#/components/schemas/PaginationMeta'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
        ]
    )]
    public function index(): JsonResponse
    {
        $query = Review::with(['user', 'destination'])->latest();

        if (request('status') === 'pending') {
            $query->whereNull('approved_at');
        } elseif (request('status') === 'approved') {
            $query->whereNotNull('approved_at');
        }

        $reviews = $query->paginate(20);

        return response()->json([
            'success' => true,
            'message' => 'Data ulasan berhasil diambil.',
            'data'    => ReviewResource::collection($reviews)->response()->getData(true)['data'],
            'meta'    => [
                'current_page' => $reviews->currentPage(),
                'last_page'    => $reviews->lastPage(),
                'per_page'     => $reviews->perPage(),
                'total'        => $reviews->total(),
            ],
        ]);
    }

    #[OA\Patch(
        path: '/api/admin/reviews/{id}/approve',
        summary: '[Admin] Setujui ulasan',
        security: [['sanctum' => []]],
        tags: ['Admin - Reviews'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 6),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Ulasan berhasil disetujui'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Ulasan tidak ditemukan'),
        ]
    )]
    public function approve(Review $review): JsonResponse
    {
        $review->update(['approved_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Ulasan berhasil disetujui.',
            'data'    => new ReviewResource($review->load('user')),
        ]);
    }

    #[OA\Patch(
        path: '/api/admin/reviews/{id}/reject',
        summary: '[Admin] Tolak ulasan (kembalikan ke pending)',
        security: [['sanctum' => []]],
        tags: ['Admin - Reviews'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 6),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Ulasan berhasil ditolak'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Ulasan tidak ditemukan'),
        ]
    )]
    public function reject(Review $review): JsonResponse
    {
        $review->update(['approved_at' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Ulasan berhasil ditolak.',
            'data'    => new ReviewResource($review->load('user')),
        ]);
    }

    #[OA\Delete(
        path: '/api/admin/reviews/{id}',
        summary: '[Admin] Hapus ulasan',
        security: [['sanctum' => []]],
        tags: ['Admin - Reviews'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 6),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Ulasan berhasil dihapus'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Ulasan tidak ditemukan'),
        ]
    )]
    public function destroy(Review $review): JsonResponse
    {
        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ulasan berhasil dihapus.',
        ]);
    }
}
