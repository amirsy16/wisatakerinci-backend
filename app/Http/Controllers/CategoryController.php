<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class CategoryController extends Controller
{
    #[OA\Get(
        path: '/api/categories',
        summary: 'Daftar semua kategori destinasi (publik)',
        tags: ['Categories'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Berhasil',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Category')),
                    ]
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $categories = Category::orderBy('name')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data kategori berhasil diambil.',
            'data'    => CategoryResource::collection($categories),
        ]);
    }
}
