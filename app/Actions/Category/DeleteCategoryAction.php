<?php

namespace App\Actions\Category;

use App\Models\Category;
use Exception;

class DeleteCategoryAction
{
    public function execute(Category $category)
    {
        if($category->subcategories()->exists()) {
            throw new Exception("لا يمكن حذف هذا القسم لأنه يحتوي على أقسام فرعية.");
        }

        if($category->products()->exists()) {
            throw new Exception("لا يمكن حذف هذا القسم لأنه يحتوي على منتجات.");
        }

        return $category->delete();
    }
}
