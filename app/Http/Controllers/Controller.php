<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Explore Kerinci API',
    description: 'REST API backend untuk aplikasi pariwisata Explore Kerinci. Gunakan endpoint `/api/auth/login` untuk mendapatkan token, lalu klik tombol **Authorize** dan masukkan token tersebut.',
    contact: new OA\Contact(name: 'Explore Kerinci', email: 'admin@wisker.test')
)]
#[OA\Server(
    url: 'http://127.0.0.1:8000',
    description: 'Development Server (php artisan serve)'
)]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'Token',
    description: 'Masukkan token Sanctum di sini. Contoh: **Bearer 1|abc...**'
)]
abstract class Controller
{
    //
}
