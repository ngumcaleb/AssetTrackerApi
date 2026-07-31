<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\RespondsWithJson;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCategoryRequest;
use App\Http\Requests\Api\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use RespondsWithJson;

    public function index()
    {
        $categories = Category::withCount('assets')
            ->orderBy('name')
            ->get();

        return $this->respondCollection(CategoryResource::collection($categories));
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return $this->respond(new CategoryResource($category->loadCount('assets')), 201);
    }

    public function show(Category $category): JsonResponse
    {
        return $this->respond(
            new CategoryResource($category->loadCount('assets'))
        );
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $data = $request->safe()->only(['name', 'icon', 'description', 'is_active']);

        if (isset($data['name']) && $data['name'] !== $category->name) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);

        return $this->respond(new CategoryResource($category->fresh()->loadCount('assets')));
    }

    public function destroy(Category $category): JsonResponse
    {
        if ($category->assets()->exists()) {
            return $this->error('Cannot delete category with associated assets.', 400);
        }

        $category->delete();

        return $this->message('Category deleted successfully.');
    }
}
