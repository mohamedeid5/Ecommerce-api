<?php

namespace App\Services;

use App\Actions\Product\UploadProductImagesAction;
use Illuminate\Support\Facades\DB;
use App\DTOs\Product\ProductDTO;
use App\Models\Product;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class ProductService
{

    public function __construct(
        private UploadProductImagesAction $uploadImagesAction
    ) {}

    public function getAll(int $perPage = 10)
    {
        return QueryBuilder::for(Product::class)
            ->with(['category', 'primaryImage', 'galleryImages'])
            ->allowedFilters(
                'name',
                AllowedFilter::exact('is_active'),
                AllowedFilter::exact('category_id'),
                AllowedFilter::scope('price_between'),
                AllowedFilter::scope('search'),
            )
            ->allowedSorts('name', 'price', 'created_at')
            ->defaultSort('-created_at')
            ->paginate($perPage);
    }

    public function createProduct(ProductDTO $dto)
    {

        return DB::transaction(function () use ($dto,) {

            $product = Product::create([
                'category_id' => $dto->category_id,
                'name'        => $dto->name,
                'description' => $dto->description,
                'price'       => $dto->price,
                'stock'       => $dto->stock,
                'is_active'   => $dto->is_active,
            ]);

            if ($dto->primaryImage) {
                $this->uploadImagesAction->uploadPrimary($product, $dto->primaryImage);
            }

            if($dto->galleryImages) {
                $this->uploadImagesAction->uploadGallery($product, $dto->galleryImages);
            }

            return $product->load(['category', 'primaryImage', 'galleryImages']);
        });

    }

    public function updateProduct(Product $product, ProductDTO $dto)
    {
        return DB::transaction(function () use ($product, $dto) {
           $product->update([
                'category_id' => $dto->category_id ?? $product->category_id,
                'name'        => $dto->name ?? $product->name,
                'description' => $dto->description ?? $product->description,
                'price'       => $dto->price ?? $product->price,
                'stock'       => $dto->stock ?? $product->stock,
                'is_active'   => $dto->is_active ?? $product->is_active,
           ]);

           if ($dto->primaryImage) {
                $this->uploadImagesAction->uploadPrimary($product, $dto->primaryImage);
            }

            if($dto->galleryImages) {
                $this->uploadImagesAction->uploadGallery($product, $dto->galleryImages);
            }

            return $product->refresh()->load(['category', 'primaryImage', 'galleryImages']);
        });
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();
    }

    public function trashedProducts()
    {
        return Product::onlyTrashed()
            ->with(['category', 'primaryImage', 'galleryImages'])
            ->paginate(10);
    }

    public function restoreProduct($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();
    }

    public function forceDeleteProduct($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->forceDelete();
    }
}
