<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('assets')->orderBy('name')->get();

        return CategoryResource::collection($categories);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'icon' => $request->icon,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
        ]);

        return (new CategoryResource($category))->response()->setStatusCode(201);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json(new CategoryResource($category->loadCount('assets')->load('assets')));
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|string|max:255|unique:categories,name,' . $category->id,
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'is_active' => 'sometimes|boolean',
        ]);

        $category->update($request->only(['name', 'icon', 'description', 'is_active']));

        return response()->json(new CategoryResource($category));
    }

    public function destroy(Category $category): JsonResponse
    {
        if ($category->assets()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete category with associated assets.',
            ], 400);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully.']);
    }
}
