<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadPhotoRequest;
use App\Http\Resources\DestinationImageResource;
use App\Models\Destination;
use App\Models\DestinationImage;
use Cloudinary\Cloudinary;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class PhotoController extends Controller
{
    private Cloudinary $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => ['secure' => true],
        ]);
    }

    #[OA\Post(
        path: '/api/admin/destinations/{id}/photos',
        summary: 'Upload foto untuk destinasi (Admin)',
        security: [['sanctum' => []]],
        tags: ['Admin - Photos'],
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
                        new OA\Property(property: 'photo', type: 'string', format: 'binary', description: 'File gambar (max 5MB, jpeg/png/jpg/webp)'),
                        new OA\Property(property: 'is_primary', type: 'boolean', example: true, description: 'Jadikan foto utama'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Foto berhasil diunggah ke Cloudinary',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/DestinationImage'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden - Admin only'),
            new OA\Response(response: 404, description: 'Destinasi tidak ditemukan'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function store(UploadPhotoRequest $request, int $id): JsonResponse
    {
        $destination = Destination::findOrFail($id);

        $result = $this->cloudinary->uploadApi()->upload(
            $request->file('photo')->getRealPath(),
            [
                'folder'        => 'wisatakerinci/destinations',
                'resource_type' => 'image',
            ]
        );

        if ($request->boolean('is_primary')) {
            $destination->images()->update(['is_primary' => false]);
        }

        $image = DestinationImage::create([
            'destination_id'       => $destination->id,
            'image_path'           => $result['secure_url'],
            'cloudinary_public_id' => $result['public_id'],
            'is_primary'           => $request->boolean('is_primary', false),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil diunggah.',
            'data'    => new DestinationImageResource($image),
        ], 201);
    }

    #[OA\Delete(
        path: '/api/admin/destinations/{destinationId}/photos/{photoId}',
        summary: 'Hapus foto destinasi (Admin)',
        security: [['sanctum' => []]],
        tags: ['Admin - Photos'],
        parameters: [
            new OA\Parameter(name: 'destinationId', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'photoId', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Foto berhasil dihapus',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden - Admin only'),
            new OA\Response(response: 404, description: 'Foto tidak ditemukan'),
        ]
    )]
    public function destroy(int $destinationId, int $photoId): JsonResponse
    {
        $image = DestinationImage::where('destination_id', $destinationId)
            ->where('id', $photoId)
            ->firstOrFail();

        // Hapus dari Cloudinary jika ada public_id
        if ($image->cloudinary_public_id) {
            $this->cloudinary->uploadApi()->destroy($image->cloudinary_public_id);
        }

        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil dihapus.',
        ]);
    }
}
