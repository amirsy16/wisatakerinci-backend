<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use OpenApi\Attributes as OA;

class UserProfileController extends Controller
{
    #[OA\Get(
        path: '/api/user/profile',
        summary: 'Ambil profil pengguna yang sedang login',
        security: [['sanctum' => []]],
        tags: ['User Profile'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Berhasil',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/User'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function show(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diambil.',
            'data'    => new UserResource($request->user()),
        ]);
    }

    #[OA\Post(
        path: '/api/user/profile',
        summary: 'Perbarui profil (nama, email, avatar, password)',
        description: 'Gunakan **POST** dengan `_method=PUT` (form-data) atau kirim langsung sebagai PUT (JSON). Semua field bersifat opsional.',
        security: [['sanctum' => []]],
        tags: ['User Profile'],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: '_method', type: 'string', example: 'PUT', description: 'Spoofing metode HTTP untuk form-data'),
                        new OA\Property(property: 'name', type: 'string', example: 'Budi Santoso Baru'),
                        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'budi.baru@example.com'),
                        new OA\Property(property: 'avatar', type: 'string', format: 'binary', description: 'File gambar avatar (max 2MB)'),
                        new OA\Property(property: 'current_password', type: 'string', format: 'password', description: 'Diperlukan jika ingin ganti password'),
                        new OA\Property(property: 'password', type: 'string', format: 'password', example: 'newpassword123'),
                        new OA\Property(property: 'password_confirmation', type: 'string', format: 'password', example: 'newpassword123'),
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Profil berhasil diperbarui',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/User'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 422, description: 'Validasi gagal'),
        ]
    )]
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->safe()->only(['name', 'email']);

        // Ganti password
        if ($request->filled('password')) {
            if (! Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal.',
                    'errors'  => ['current_password' => ['Password lama tidak sesuai.']],
                ], 422);
            }
            $data['password'] = Hash::make($request->password);
        }

        // Upload avatar
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data'    => new UserResource($user->fresh()),
        ]);
    }

    #[OA\Get(
        path: '/api/user/reviews',
        summary: 'Riwayat ulasan milik pengguna yang sedang login',
        security: [['sanctum' => []]],
        tags: ['User Profile'],
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
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Review')),
                        new OA\Property(property: 'meta', ref: '#/components/schemas/PaginationMeta'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]
    public function reviews(Request $request): JsonResponse
    {
        $reviews = $request->user()
            ->reviews()
            ->with('destination')
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Riwayat ulasan berhasil diambil.',
            'data'    => ReviewResource::collection($reviews)->response()->getData(true)['data'],
            'meta'    => [
                'current_page' => $reviews->currentPage(),
                'last_page'    => $reviews->lastPage(),
                'per_page'     => $reviews->perPage(),
                'total'        => $reviews->total(),
            ],
        ]);
    }
}
