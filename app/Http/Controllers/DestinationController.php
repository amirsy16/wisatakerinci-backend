<?php

namespace App\Http\Controllers;

use App\Http\Resources\DestinationResource;
use App\Services\DestinationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class DestinationController extends Controller
{
    public function __construct(
        private readonly DestinationService $destinationService
    ) {}

    #[OA\Get(
        path: '/api/destinations',
        summary: 'Daftar destinasi wisata (publik)',
        tags: ['Destinations'],
        parameters: [
            new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string'), description: 'Cari berdasarkan nama atau deskripsi', example: 'gunung'),
            new OA\Parameter(name: 'category', in: 'query', required: false, schema: new OA\Schema(type: 'string'), description: 'Filter berdasarkan slug kategori', example: 'alam-pegunungan'),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 10), description: 'Jumlah data per halaman'),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 1), description: 'Nomor halaman'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Berhasil',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Destination')),
                        new OA\Property(property: 'meta', ref: '#/components/schemas/PaginationMeta'),
                    ]
                )
            ),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $destinations = $this->destinationService->getAll($request->only(['search', 'category', 'per_page']));

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

    #[OA\Get(
        path: '/api/destinations/{slug}',
        summary: 'Detail destinasi berdasarkan slug (publik)',
        tags: ['Destinations'],
        parameters: [
            new OA\Parameter(name: 'slug', in: 'path', required: true, schema: new OA\Schema(type: 'string'), example: 'gunung-kerinci'),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Berhasil',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/Destination'),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Destinasi tidak ditemukan'),
        ]
    )]
    public function show(string $slug): JsonResponse
    {
        $destination = $this->destinationService->getBySlug($slug);

        return response()->json([
            'success' => true,
            'message' => 'Detail destinasi berhasil diambil.',
            'data'    => new DestinationResource($destination),
        ]);
    }
}
