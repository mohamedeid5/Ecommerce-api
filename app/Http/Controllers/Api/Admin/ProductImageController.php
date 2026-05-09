<?php

namespace App\Http\Controllers\Api\Admin;

use App\Actions\Product\DeleteProductImageAction;
use App\Actions\Product\ReorderProductImagesAction;
use App\Actions\Product\UploadProductImagesAction;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Admin\Product\ReorderProductImagesRequest;
use App\Http\Requests\Admin\Product\UploadGalleryImagesRequest;
use App\Http\Requests\Admin\Product\UploadPrimaryImageRequest;
use App\Http\Resources\ProductImageResource;
use App\Models\Product;
use App\Models\ProductImage;

class ProductImageController extends BaseApiController
{
    public function __construct(
         private UploadProductImagesAction $uploadProductImagesAction,
         private DeleteProductImageAction $deleteProductImageAction,
         private ReorderProductImagesAction $recordProductImageAction
    ) {}

    public function uploadPrimary(UploadPrimaryImageRequest $request, Product $product)
    {
        $image = $this->uploadProductImagesAction->uploadPrimary(
            $product,
            $request->file('image')
        );

        return $this->successResponse(
            new ProductImageResource($image),
            'Primary image uploaded successfully'
        );
    }

    public function uploadGallery(UploadGalleryImagesRequest $request, Product $product)
    {
        $images = $this->uploadProductImagesAction->uploadGallery(
            $product,
            $request->file('images')
        );

        return $this->successResponse(
            ProductImageResource::collection($images),
            'Gallery images uploaded successfully'
        );
    }

    public function destroy(ProductImage $image)
    {
        $this->deleteProductImageAction->handle($image);

        return $this->successResponse(
            null,
            'image deleted successfully'
        );
    }

    public function reorder(ReorderProductImagesRequest $request, Product $product)
    {
        $this->recordProductImageAction->handle($product, $request->input('image_ids'));

        return $this->successResponse(
            null,
            'image recorded successfully'
        );
    }
}
