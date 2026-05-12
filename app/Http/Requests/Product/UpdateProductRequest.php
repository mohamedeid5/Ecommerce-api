<?php

namespace App\Http\Requests\Product;

use App\Enums\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['sometimes', 'required', 'exists:categories,id'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'sale_price' => ['sometimes', 'numeric', 'min:0', 'lt:price'],
            'stock' => ['sometimes', 'required', 'integer', 'min:0'],
            'sku' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('products')->ignore($this->product)
            ],
            'status' => ['nullable', Rule::enum(ProductStatus::class)],
            'primary_image' => ['nullable', 'image', 'mimes:png,gif,jpg,jpeg,webp', 'max:2048'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['image', 'mimes:png,gif,jpg,jpeg,webp', 'max:2048'],
        ];
    }
}
