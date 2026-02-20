<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ReviewController extends Controller
{
    #[OA\Post(
        path: '/api/reviews',
        summary: 'Kirim ulasan untuk destinasi',
        security: [['sanctum' => []]],
        tags: ['Reviews'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['destination_id', 'rating', 'comment'],
                properties: [
                    new OA\Property(property: 'destination_id', type: 'integer', example: 1),
                    new OA\Property(property: 'rating', type: 'integer', minimum: 1, maximum: 5, example: 5),
                    new OA\Property(property: 'comment', type: 'string', minLength: 10, example: 'Pemandangan sangat menakjubkan!'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Ulasan berhasil dikirim (menunggu persetujuan)',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Review'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function store(StoreReviewRequest $request): JsonResponse
    {
        $review = Review::create([
            'user_id'        => $request->user()->id,
            'destination_id' => $request->destination_id,
            'rating'         => $request->rating,
            'comment'        => $request->comment,
        ]);

        $review->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Ulasan berhasil dikirim dan menunggu persetujuan.',
            'data'    => new ReviewResource($review),
        ], 201);
    }
}
