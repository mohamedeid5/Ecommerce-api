<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {

    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
         $product->load('primaryImage', 'galleryImages');

        if($product->primaryImage) {
            Storage::disk('public')->delete($product->primaryImage->path);
        }

        if($product->galleryImages) {
            $product->galleryImages->each(function ($image) {
                Storage::disk('public')->delete($image->path);
            });
        }
    }
}
