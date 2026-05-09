<?php

namespace App\Actions\Product;

use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class DeleteProductImageAction
{
     public function handle(ProductImage $image): void
    {
        Storage::disk('public')->delete($image->path);
        $image->delete();
    }
}
