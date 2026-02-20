<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadPhotoRequest;
use App\Http\Resources\DestinationImageResource;
use App\Models\Destination;
use App\Models\DestinationImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use OpenApi\Attributes as OA;

class PhotoController extends Controller
{
    #[OA\Post(
        path: '/api/destinations/{id}/photos',
        summary: 'Upload foto untuk destinasi',
        security: [['sanctum' => []]],
        tags: ['Photos'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    required: ['photo'],
                    properties: [
                        new OA\Property(property: 'photo', type: 'string', format: 'binary', description: 'File gambar (max 5MB)'),
                        new OA\Property(property: 'is_primary', type: 'boolean', example: true, description: 'Jadikan foto utama'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Foto berhasil diunggah',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/DestinationImage'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 404, description: 'Destinasi tidak ditemukan'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function store(UploadPhotoRequest $request, int $id): JsonResponse
    {
        $destination = Destination::findOrFail($id);

        $path = $request->file('photo')->store('destinations', 'public');

        // If marked as primary, unset all existing primaries first
        if ($request->boolean('is_primary')) {
            $destination->images()->update(['is_primary' => false]);
        }

        $image = DestinationImage::create([
            'destination_id' => $destination->id,
            'image_path'     => $path,
            'is_primary'     => $request->boolean('is_primary', false),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil diunggah.',
            'data'    => new DestinationImageResource($image),
        ], 201);
    }
}
