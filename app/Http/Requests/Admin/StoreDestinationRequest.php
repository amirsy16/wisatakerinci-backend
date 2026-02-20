<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreDestinationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:255'],
            'slug'         => ['nullable', 'string', 'max:255', 'unique:destinations,slug'],
            'description'  => ['required', 'string'],
            'location'     => ['required', 'string', 'max:255'],
            'map_url'      => ['nullable', 'url'],
            'ticket_price' => ['required', 'numeric', 'min:0'],
            'open_hours'   => ['nullable', 'string', 'max:100'],
            'status'       => ['required', 'in:active,draft,inactive'],
            'categories'   => ['sometimes', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (empty($this->slug) && $this->name) {
            $this->merge(['slug' => Str::slug($this->name)]);
        }
    }
}
