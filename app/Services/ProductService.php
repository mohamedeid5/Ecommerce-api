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

    private array $relations = ['category', 'primaryImage', 'galleryImages'];

    public function __construct(
        private UploadProductImagesAction $uploadImagesAction
    ) {}

    public function getAll(int $perPage = 10)
    {
        return QueryBuilder::for(Product::class)
            ->with($this->relations)
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

        return DB::transaction(function () use ($dto) {

            $product = Product::create([
                'category_id' => $dto->category_id,
                'name'        => $dto->name,
                'description' => $dto->description,
                'price'       => $dto->price,
                'stock'       => $dto->stock,
                'sku'         => $dto->sku,
                'status'   => $dto->status,
            ]);

            if ($dto->primaryImage) {
                $this->uploadImagesAction->uploadPrimary($product, $dto->primaryImage);
            }

            if($dto->galleryImages) {
                $this->uploadImagesAction->uploadGallery($product, $dto->galleryImages);
            }

            return $product->load($this->relations);
        });

    }

    public function updateProduct(Product $product, ProductDTO $dto)
    {
    return DB::transaction(function () use ($product, $dto) {

        $data = array_filter([
            'category_id' => $dto->category_id,
            'name'        => $dto->name,
            'description' => $dto->description,
            'price'       => $dto->price,
            'stock'       => $dto->stock,
            'sku'         => $dto->sku,
            'status'      => $dto->status,
        ], fn($value)=>!is_null($value));

        $product->update($data);

        if ($dto->primaryImage) {
            $this->uploadImagesAction->uploadPrimary($product, $dto->primaryImage);
        }

        if($dto->galleryImages) {
            $this->uploadImagesAction->uploadGallery($product, $dto->galleryImages);
        }

        return $product->refresh()->load($this->relations);
    });
    }

    public function deleteProduct(Product $product)
    {
        return $product->delete();
    }

    public function trashedProducts()
    {
        return Product::onlyTrashed()
            ->with($this->relations)
            ->paginate(10);
    }

    public function restoreProduct($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        return $product->restore();
    }

    public function forceDeleteProduct($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        return $product->forceDelete();
    }
}
