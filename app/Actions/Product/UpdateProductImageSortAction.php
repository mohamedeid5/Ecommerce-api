<?php

namespace App\Actions\Product;

use App\Models\ProductImage;

class DeleteProductImageAction
{
     public function handle(ProductImage $image, int $sortOrder): ProductImage
    {
       $image->update([
            'sort_order' => $sortOrder
       ]);

       return $image->fresh();
    }
}
