<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                  => ['sometimes', 'string', 'max:255'],
            'email'                 => ['sometimes', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user()->id],
            'avatar'                => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'current_password'      => ['required_with:password', 'string'],
            'password'              => ['sometimes', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required_with:password', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'                    => 'Email sudah digunakan akun lain.',
            'current_password.required_with'  => 'Password lama wajib diisi saat mengganti password.',
            'password.min'                    => 'Password baru minimal 8 karakter.',
            'password.confirmed'              => 'Konfirmasi password baru tidak cocok.',
            'avatar.image'                    => 'File harus berupa gambar.',
            'avatar.max'                      => 'Ukuran avatar maksimal 2 MB.',
        ];
    }
}
