<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadPhotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'photo'      => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'is_primary' => ['sometimes', 'in:0,1,true,false'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('is_primary')) {
            $this->merge([
                'is_primary' => filter_var($this->is_primary, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }

    public function messages(): array
    {
        return [
            'photo.required' => 'Foto wajib diunggah.',
            'photo.image'    => 'File harus berupa gambar.',
            'photo.mimes'    => 'Format foto harus jpeg, png, jpg, atau webp.',
            'photo.max'      => 'Ukuran foto maksimal 5 MB.',
        ];
    }
}
