<?php

namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadProductImagesAction
{
    public function uploadPrimary(Product $product, UploadedFile $file)
    {
        $oldImage = $product->primaryImage;

        if($oldImage) {
            Storage::disk('public')->delete($oldImage->path);
            $oldImage->delete();
        }

        $path = $this->storeFile($file, $product->id, 'primary');

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

            $path = $this->storeFile($file, $product->id, 'gallery');

            $uploadedImages[] = $product->images()->create([
                'path' => $path,
                'is_primary' => false,
                'sort_order' => $currentMaxOrder + $index + 1
            ]);
        }

        return $uploadedImages;
    }


    public function storeFile(UploadedFile $file, int $productId, string $filename)
    {
        return $file->store("products/{$productId}/{$filename}", 'public');
    }
}
