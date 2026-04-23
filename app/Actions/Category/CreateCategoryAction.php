<?php

namespace App\Actions\Category;

use App\DTOs\Category\CategoryDTO;
use App\Models\Category;

class CreateCategoryAction
{
    public function execute(CategoryDTO $dto): Category
    {
        $data = [
            'parent_id' => $dto->parent_id,
            'name' => $dto->name,
            'description' => $dto->description,
            'is_active' => $dto->is_active,
        ];

        return Category::create($data);
    }
}
