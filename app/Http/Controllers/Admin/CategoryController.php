<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class CategoryController extends Controller
{
    #[OA\Get(
        path: '/api/admin/categories',
        summary: '[Admin] Daftar semua kategori dengan jumlah destinasi',
        security: [['sanctum' => []]],
        tags: ['Admin - Categories'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Berhasil',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer'),
                                    new OA\Property(property: 'name', type: 'string'),
                                    new OA\Property(property: 'slug', type: 'string'),
                                    new OA\Property(property: 'destinations_count', type: 'integer'),
                                ]
                            )
                        ),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
        ]
    )]
    public function index(): JsonResponse
    {
        $categories = Category::withCount('destinations')->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data kategori berhasil diambil.',
            'data'    => $categories->map(fn ($c) => [
                'id'                 => $c->id,
                'name'               => $c->name,
                'slug'               => $c->slug,
                'destinations_count' => $c->destinations_count,
            ]),
        ]);
    }

    #[OA\Post(
        path: '/api/admin/categories',
        summary: '[Admin] Tambah kategori baru',
        security: [['sanctum' => []]],
        tags: ['Admin - Categories'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Wisata Bawah Air'),
                    new OA\Property(property: 'slug', type: 'string', example: 'wisata-bawah-air', description: 'Opsional, auto-generate dari name jika tidak diisi'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Kategori berhasil ditambahkan'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan.',
            'data'    => new CategoryResource($category),
        ], 201);
    }

    #[OA\Put(
        path: '/api/admin/categories/{id}',
        summary: '[Admin] Update kategori',
        security: [['sanctum' => []]],
        tags: ['Admin - Categories'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1),
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Nama Baru'),
                    new OA\Property(property: 'slug', type: 'string', example: 'nama-baru'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Kategori berhasil diperbarui'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Tidak ditemukan'),
        ]
    )]
    public function update(StoreCategoryRequest $request, Category $category): JsonResponse
    {
        $category->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui.',
            'data'    => new CategoryResource($category),
        ]);
    }

    #[OA\Delete(
        path: '/api/admin/categories/{id}',
        summary: '[Admin] Hapus kategori',
        security: [['sanctum' => []]],
        tags: ['Admin - Categories'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Kategori berhasil dihapus'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Tidak ditemukan'),
        ]
    )]
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus.',
        ]);
    }
}
