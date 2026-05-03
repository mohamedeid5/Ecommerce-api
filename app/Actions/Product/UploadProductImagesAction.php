<?php

namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Http\UploadedFile;

class UploadProductImagesAction
{
    public function uploadPrimary(Product $product, UploadedFile $file)
    {
        $product->primaryImage()->delete();

        $path = $this->storeFile($file);

        return $product->images()->create([
            'path' => $path,
            'is_primary' => true,
            'sort_order' => 0,
        ]);
    }

    public function uploadGallery(Product $product, array $files)
    {
        $uploadedImages = [];

        $currentMaxOrder = $product->galleryImages()->max('sort_order') ?? 0;

        foreach ($files as $index => $file) {

            $path = $this->storeFile($file);

            $uploadedImages[] = $product->images()->create([
                'path' => $path,
                'is_primary' => false,
                'sort_order' => $currentMaxOrder + $index + 1
            ]);
        }

        return $uploadedImages;
    }


    public function storeFile(UploadedFile $file)
    {
        return $file->store('products', 'public');
    }
}
