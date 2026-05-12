<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Actions\Category\CreateCategoryAction;
use App\Actions\Category\DeleteCategoryAction;
use App\Actions\Category\GetCategoriesAction;
use App\Actions\Category\UpdateCategoryAction;
use App\DTOs\Category\CategoryDTO;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Exception;

class CategoryController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetCategoriesAction $action)
    {
        $categories = $action->execute();

        return $this->successResponse(
            CategoryResource::collection($categories),
            'Categories retrieved successfully'
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request, CreateCategoryAction $action)
    {
        $dto = CategoryDTO::fromRequest($request);

        $category = $action->execute($dto);

        return $this->createdResponse(
            new CategoryResource($category),
            'Category created successfully'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return $this->successResponse(
            new CategoryResource($category),
            'Category retrieved seccessfully'
        );
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category, UpdateCategoryAction $action)
    {
        $dto = CategoryDTO::fromRequest($request);
        $category = $action->execute($category, $dto);

        return $this->successResponse(
            new CategoryResource($category),
            'Category updated successfully'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category, DeleteCategoryAction $action)
    {
        try {
            $action->execute($category);
            return $this->successResponse(
                null,
                'Category deleted successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                $e->getMessage(),
            );
        }
    }
}
