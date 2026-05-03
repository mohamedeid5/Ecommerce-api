<?php

namespace App\Actions\Category;

use App\Models\Category;
use App\DTOs\Category\CategoryDTO;

class UpdateCategoryAction
{
    public function execute(Category $category, CategoryDTO $data): Category
    {
        $category->update([
            'parent_id' => $data->parent_id,
            'name' => $data->name,
            'description' => $data->description,
            'is_active' => $data->is_active,
        ]);

        return $category->fresh(['parent', 'products']);
    }
}
