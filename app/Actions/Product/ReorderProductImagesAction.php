<?php

namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReorderProductImagesAction
{
    public function handle(Product $product, array $imageIds): void
    {
        $galleryImageIds = $product->galleryImages()->pluck('id')->toArray();

        $sentImageIds = $imageIds;

        sort($galleryImageIds);
        sort($sentImageIds);

        if($sentImageIds != $galleryImageIds) {
            throw ValidationException::withMessages([
                'image_ids' => ['The provided images do not match this product gallery images.'],
            ]);
        }

        DB::transaction(function() use ($imageIds) {
            foreach ($imageIds as $index => $imageId) {
                DB::table('product_images')
                    ->where('id', $imageId)
                    ->update([
                        'sort_order' => $index + 1
                    ]);
            }
        });
    }
}
