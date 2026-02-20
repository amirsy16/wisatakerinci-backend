<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDestinationRequest;
use App\Http\Requests\Admin\UpdateDestinationRequest;
use App\Http\Resources\DestinationResource;
use App\Models\Destination;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class DestinationController extends Controller
{
    #[OA\Get(
        path: '/api/admin/destinations',
        summary: '[Admin] Daftar semua destinasi',
        security: [['sanctum' => []]],
        tags: ['Admin - Destinations'],
        parameters: [
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 1)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Berhasil',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Destination')),
                        new OA\Property(property: 'meta', ref: '#/components/schemas/PaginationMeta'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden - bukan admin'),
        ]
    )]
    public function index(): JsonResponse
    {
        $destinations = Destination::with(['categories', 'images'])
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Data destinasi berhasil diambil.',
            'data'    => DestinationResource::collection($destinations)->response()->getData(true)['data'],
            'meta'    => [
                'current_page' => $destinations->currentPage(),
                'last_page'    => $destinations->lastPage(),
                'per_page'     => $destinations->perPage(),
                'total'        => $destinations->total(),
            ],
        ]);
    }

    #[OA\Post(
        path: '/api/admin/destinations',
        summary: '[Admin] Tambah destinasi baru',
        security: [['sanctum' => []]],
        tags: ['Admin - Destinations'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'description', 'location'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Air Terjun Telun Berasap'),
                    new OA\Property(property: 'description', type: 'string', example: 'Air terjun yang indah di Kerinci...'),
                    new OA\Property(property: 'location', type: 'string', example: 'Kayu Aro, Kerinci'),
                    new OA\Property(property: 'ticket_price', type: 'number', example: 15000),
                    new OA\Property(property: 'open_hours', type: 'string', example: '07:00 - 17:00'),
                    new OA\Property(property: 'status', type: 'string', enum: ['active', 'inactive'], example: 'active'),
                    new OA\Property(property: 'categories', type: 'array', items: new OA\Items(type: 'integer'), example: [1, 2]),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Destinasi berhasil ditambahkan'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function store(StoreDestinationRequest $request): JsonResponse
    {
        $destination = Destination::create($request->safe()->except('categories'));

        if ($request->filled('categories')) {
            $destination->categories()->sync($request->categories);
        }

        $destination->load(['categories', 'images']);

        return response()->json([
            'success' => true,
            'message' => 'Destinasi berhasil ditambahkan.',
            'data'    => new DestinationResource($destination),
        ], 201);
    }

    #[OA\Get(
        path: '/api/admin/destinations/{id}',
        summary: '[Admin] Detail destinasi',
        security: [['sanctum' => []]],
        tags: ['Admin - Destinations'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Berhasil'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Tidak ditemukan'),
        ]
    )]
    public function show(Destination $destination): JsonResponse
    {
        $destination->load(['categories', 'images', 'approvedReviews.user']);

        return response()->json([
            'success' => true,
            'message' => 'Detail destinasi berhasil diambil.',
            'data'    => new DestinationResource($destination),
        ]);
    }

    #[OA\Put(
        path: '/api/admin/destinations/{id}',
        summary: '[Admin] Update destinasi',
        security: [['sanctum' => []]],
        tags: ['Admin - Destinations'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1),
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Nama Baru'),
                    new OA\Property(property: 'description', type: 'string'),
                    new OA\Property(property: 'location', type: 'string'),
                    new OA\Property(property: 'ticket_price', type: 'number'),
                    new OA\Property(property: 'open_hours', type: 'string'),
                    new OA\Property(property: 'status', type: 'string', enum: ['active', 'inactive']),
                    new OA\Property(property: 'categories', type: 'array', items: new OA\Items(type: 'integer')),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Destinasi berhasil diperbarui'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Tidak ditemukan'),
        ]
    )]
    public function update(UpdateDestinationRequest $request, Destination $destination): JsonResponse
    {
        $destination->update($request->safe()->except('categories'));

        if ($request->has('categories')) {
            $destination->categories()->sync($request->categories);
        }

        $destination->load(['categories', 'images']);

        return response()->json([
            'success' => true,
            'message' => 'Destinasi berhasil diperbarui.',
            'data'    => new DestinationResource($destination),
        ]);
    }

    #[OA\Delete(
        path: '/api/admin/destinations/{id}',
        summary: '[Admin] Hapus destinasi',
        security: [['sanctum' => []]],
        tags: ['Admin - Destinations'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Destinasi berhasil dihapus'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Tidak ditemukan'),
        ]
    )]
    public function destroy(Destination $destination): JsonResponse
    {
        $destination->delete();

        return response()->json([
            'success' => true,
            'message' => 'Destinasi berhasil dihapus.',
        ]);
    }
}
