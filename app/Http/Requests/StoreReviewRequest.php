<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'destination_id' => ['required', 'integer', 'exists:destinations,id'],
            'rating'         => ['required', 'integer', 'min:1', 'max:5'],
            'comment'        => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'destination_id.required' => 'Destinasi wajib dipilih.',
            'destination_id.exists'   => 'Destinasi tidak ditemukan.',
            'rating.required'         => 'Rating wajib diisi.',
            'rating.min'              => 'Rating minimal 1.',
            'rating.max'              => 'Rating maksimal 5.',
            'comment.required'        => 'Komentar wajib diisi.',
            'comment.min'             => 'Komentar minimal 10 karakter.',
        ];
    }
}
