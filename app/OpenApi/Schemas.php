<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

/**
 * OpenAPI schema definitions for Explore Kerinci API.
 * This file is scanned by L5-Swagger automatically.
 */

#[OA\Schema(
    schema: 'User',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Budi Santoso'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'budi@example.com'),
        new OA\Property(property: 'avatar_url', type: 'string', nullable: true, example: 'http://127.0.0.1:8000/storage/avatars/photo.jpg'),
        new OA\Property(property: 'role', type: 'string', enum: ['user', 'admin'], example: 'user'),
    ]
)]
#[OA\Schema(
    schema: 'Category',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Alam Pegunungan'),
        new OA\Property(property: 'slug', type: 'string', example: 'alam-pegunungan'),
    ]
)]
#[OA\Schema(
    schema: 'DestinationImage',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'image_url', type: 'string', example: 'http://127.0.0.1:8000/storage/destinations/img.jpg'),
        new OA\Property(property: 'is_primary', type: 'boolean', example: true),
    ]
)]
#[OA\Schema(
    schema: 'Review',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'rating', type: 'integer', minimum: 1, maximum: 5, example: 5),
        new OA\Property(property: 'comment', type: 'string', example: 'Pemandangan sangat indah!'),
        new OA\Property(property: 'approved_at', type: 'string', format: 'date-time', nullable: true, example: '2024-01-15T10:30:00Z'),
        new OA\Property(property: 'user', ref: '#/components/schemas/User', nullable: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2024-01-15T10:00:00Z'),
    ]
)]
#[OA\Schema(
    schema: 'Destination',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Gunung Kerinci'),
        new OA\Property(property: 'slug', type: 'string', example: 'gunung-kerinci'),
        new OA\Property(property: 'description', type: 'string', example: 'Gunung tertinggi di Sumatera...'),
        new OA\Property(property: 'location', type: 'string', example: 'Sungai Penuh, Kerinci'),
        new OA\Property(property: 'ticket_price', type: 'number', format: 'float', example: 25000),
        new OA\Property(property: 'open_hours', type: 'string', example: '06:00 - 18:00'),
        new OA\Property(property: 'status', type: 'string', enum: ['active', 'inactive'], example: 'active'),
        new OA\Property(property: 'rating_avg', type: 'number', format: 'float', nullable: true, example: 4.8),
        new OA\Property(property: 'review_count', type: 'integer', example: 30),
        new OA\Property(property: 'categories', type: 'array', items: new OA\Items(ref: '#/components/schemas/Category')),
        new OA\Property(property: 'images', type: 'array', items: new OA\Items(ref: '#/components/schemas/DestinationImage')),
        new OA\Property(property: 'reviews', type: 'array', items: new OA\Items(ref: '#/components/schemas/Review')),
    ]
)]
#[OA\Schema(
    schema: 'PaginationMeta',
    properties: [
        new OA\Property(property: 'current_page', type: 'integer', example: 1),
        new OA\Property(property: 'last_page', type: 'integer', example: 5),
        new OA\Property(property: 'per_page', type: 'integer', example: 10),
        new OA\Property(property: 'total', type: 'integer', example: 50),
    ]
)]
class Schemas {}
