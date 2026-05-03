<?php

namespace App\Observers;

use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductImageObserver
{
    /**
     * Handle the ProductImage "created" event.
     */
    public function created(ProductImage $productImage): void
    {
        //
    }

    /**
     * Handle the ProductImage "updated" event.
     */
    public function updated(ProductImage $image): void
    {
        Storage::disk('public')->delete($image->path);
    }

    /**
     * Handle the ProductImage "deleted" event.
     */
    public function deleted(ProductImage $image): void
    {
        Storage::disk('public')->delete($image->path);
    }

    /**
     * Handle the ProductImage "restored" event.
     */
    public function restored(ProductImage $productImage): void
    {
        //
    }

    /**
     * Handle the ProductImage "force deleted" event.
     */
    public function forceDeleted(ProductImage $productImage): void
    {
        //
    }
}
