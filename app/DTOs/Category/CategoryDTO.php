<?php

namespace App\DTOs\Category;

class CategoryDTO
{
    public function __construct(
        public ?int $parent_id,
        public string $name,
        public ?string $description,
        public bool $is_active,
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
            parent_id: $request->validated('parent_id'),
            name: $request->validated('name'),
            description: $request->validated('description'),
            is_active: $request->validated('is_active'),
        );
    }
}
