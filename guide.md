# 🏔️ Backend & API Development Guide: Explore Kerinci

> **Vibe Check:** Clean code, RESTful principles, modular, and scalable architecture.
> **Tech Stack:** Laravel (PHP), MySQL/PostgreSQL, Laravel Sanctum (Auth).

## 📌 1. Aturan Main (Core Directives)
Sebagai AI Assistant, saat menulis kode untuk proyek ini, kamu **WAJIB** mematuhi aturan berikut:
* **Gaya Penulisan Kode:** Tulis kode PHP bergaya modern (PHP 8.x), gunakan *type hinting* secara ketat, dan patuhi standar PSR-12.
* **Pemisahan Logika:** Jaga *Controller* tetap ramping (*skinny controllers*). Pindahkan logika bisnis yang kompleks ke *Service Classes* atau *Action Classes*.
* **Keamanan:** Selalu validasi *input* menggunakan **Form Requests** (jangan pernah validasi langsung di *controller*). *Sanitize* data sebelum disimpan.
* **API Response:** Selalu gunakan **API Resources** (`JsonResource`) untuk memformat *output* data agar terstruktur dan menyembunyikan kolom sensitif.

## 🗄️ 2. Struktur Database (The Blueprint)
Saat saya meminta pembuatan *migration* atau *model*, gunakan kerangka relasi berikut sebagai acuan utama:

* `users`: id, name, email, password, avatar, role (admin/user).
* `destinations`: id, name, slug, description, location, map_url, ticket_price, status.
* `destination_images`: id, destination_id, image_path, is_primary.
* `reviews`: id, user_id, destination_id, rating (1-5), comment, approved_at.
* `categories`: id, name, slug.
* `category_destination` (Pivot): category_id, destination_id.

## 🛣️ 3. Arsitektur API (Endpoints)
Gunakan *naming convention* yang standar untuk REST API.

### Public Routes (Tidak Butuh Token)
* `GET /api/destinations` -> Ambil semua wisata (dukung filter kategori & pencarian).
* `GET /api/destinations/{slug}` -> Detail wisata + *review* + galeri.
* `GET /api/categories` -> List semua kategori wisata.

### Protected Routes (Membutuhkan Token Sanctum)
* `POST /api/reviews` -> *User* mengirim ulasan dan *rating*.
* `POST /api/destinations/{id}/photos` -> *Upload* foto UGC (User Generated Content).
* `POST /api/auth/logout` -> Revoke token saat ini.

## 🛠️ 4. Workflow Eksekusi AI (SOP)
Jika saya memberikan *prompt*: *"Buatkan fitur X"*, kerjakan dengan urutan sistematis ini:
1.  Buat **Migration** & **Factory** (lengkap dengan data *dummy* yang masuk akal).
2.  Buat **Model** (definisikan `fillable`, *casts*, dan *relationships*).
3.  Buat **Form Request** (untuk aturan validasi).
4.  Buat **API Resource** (untuk *formatting* JSON).
5.  Buat **Controller** (dengan metode *CRUD* yang diminta).
6.  Berikan rute yang harus saya tambahkan ke `routes/api.php`.

## 🚀 5. Format Respons JSON Standar
Semua *response* API harus mengikuti format seragam ini:

**Sukses:**
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": { ... }
}

**Error:**
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "email": ["Email sudah terdaftar."]
  }
}