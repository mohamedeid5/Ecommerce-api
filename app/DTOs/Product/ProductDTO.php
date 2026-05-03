<?php

namespace App\DTOs\Product;

use Illuminate\Http\UploadedFile;

class ProductDTO
{
    public function __construct(
        public int $category_id,
        public string $name,
        public string $description,
        public float $price,
        public int $stock,
        public bool $is_active,
        public ?UploadedFile $primaryImage = null,
        public ?array $galleryImages = null,

    ){}

    public static function fromRequest($request): self
    {
        return new self(
            category_id: $request->validated('category_id'),
            name: $request->validated('name'),
            description: $request->validated('description'),
            price: $request->validated('price'),
            stock: $request->validated('stock'),
            is_active: $request->validated('is_active'),
            primaryImage: $request->validated('primary_image'),
            galleryImages: $request->validated('gallery_images'),
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'category_id' => $this->category_id,
            'is_active' => $this->is_active,
        ];
    }
}
