<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductImageService
{
    public function uploadPrimary($product)
    {
        $oldPrimary = $product->primaryImage;

        if($oldPrimary) {
            Storage::disk('public')->delete($oldPrimary->path);
            $oldPrimary->delete();
        }
    }
}
