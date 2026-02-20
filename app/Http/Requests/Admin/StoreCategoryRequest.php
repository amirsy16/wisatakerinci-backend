<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('category') instanceof \App\Models\Category
            ? $this->route('category')->id
            : $this->route('category');

        $uniqueSlug = $id ? "unique:categories,slug,{$id}" : 'unique:categories,slug';
        $uniqueName = $id ? "unique:categories,name,{$id}" : 'unique:categories,name';

        // PUT/PATCH = update (name opsional), POST = store (name wajib)
        $nameRequired = $this->isMethod('post') ? 'required' : 'sometimes';

        return [
            'name' => [$nameRequired, 'string', 'max:100', $uniqueName],
            'slug' => ['nullable', 'string', 'max:100', $uniqueSlug],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (empty($this->slug) && $this->name) {
            $this->merge(['slug' => Str::slug($this->name)]);
        }
    }
}
