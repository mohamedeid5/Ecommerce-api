<?php

namespace App\Http\Controllers\Api\Admin;

use App\DTOs\Product\ProductDTO;
use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Http\Requests\Admin\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;

class ProductController extends BaseApiController
{
    public function __construct(
        protected ProductService $productService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = $this->productService->getAll(10);

        return $this->successResponse(
            ProductResource::collection($products),
            'Products retrieved successfully'
    );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $dto = ProductDTO::fromRequest($request);

        $product = $this->productService->createProduct($dto);

        return $this->createdResponse(
            new ProductResource($product),
            'Product created successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'primaryImage', 'galleryImages']);

        return $this->successResponse(
            new ProductResource($product),
            'Product retrieved successfully'
        );
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $dto = ProductDTO::fromRequest($request);

        $product = $this->productService->updateProduct($product, $dto);

        return $this->successResponse(
            new ProductResource($product),
            'Product updated successfully',
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->productService->deleteProduct($product);

        return $this->successResponse(
            null,
            'Product deleted successfully'
        );
    }

    public function trashed()
    {
        $products = $this->productService->trashedProducts();

        return $this->successResponse(
            ProductResource::collection($products),
            'Trashed Proucts retrieved successfully'
        );
    }

    public function restore($id)
    {
        $this->productService->restoreProduct($id);

        return $this->successResponse(
            null,
            'Product Restored Sucessfully'
        );
    }

    public function forceDelete($id)
    {
        $this->productService->forceDeleteProduct($id);

        return $this->successResponse(
            null,
            'Product Permanently Deleted Sucessfully'
        );
    }
}
