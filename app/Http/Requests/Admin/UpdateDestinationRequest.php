<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateDestinationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $destination = $this->route('destination');
        $id = $destination instanceof \App\Models\Destination ? $destination->id : $destination;

        return [
            'name'         => ['sometimes', 'string', 'max:255'],
            'slug'         => ['sometimes', 'string', 'max:255', "unique:destinations,slug,{$id}"],
            'description'  => ['sometimes', 'string'],
            'location'     => ['sometimes', 'string', 'max:255'],
            'map_url'      => ['nullable', 'url'],
            'ticket_price' => ['sometimes', 'numeric', 'min:0'],
            'open_hours'   => ['nullable', 'string', 'max:100'],
            'status'       => ['sometimes', 'in:active,draft,inactive'],
            'categories'   => ['sometimes', 'array'],
            'categories.*' => ['integer', 'exists:categories,id'],
        ];
    }
}
