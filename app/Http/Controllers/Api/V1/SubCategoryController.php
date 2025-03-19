<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index($parentId)
    {
        $parentCategory = Category::findOrFail($parentId);
        return $parentCategory->subCategories;
    }

    public function store(Request $request, $parentId)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $parentCategory = Category::findOrFail($parentId);

        $subCategory = new Category([
            'name' => $request->name,
            'parent_id' => $parentId,
        ]);

        $parentCategory->subCategories()->save($subCategory);
        return $subCategory;
    }

    public function show($parentId, $id)
    {
        return Category::where('parent_id', $parentId)->findOrFail($id);
    }

    public function update(Request $request, $parentId, $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255|unique:categories,name,' . $id,
        ]);

        $subCategory = Category::where('parent_id', $parentId)->findOrFail($id);
        $subCategory->update($request->all());

        return $subCategory;
    }

    public function destroy($parentId, $id)
    {
        $subCategory = Category::where('parent_id', $parentId)->findOrFail($id);
        $subCategory->delete();

        return response()->noContent();
    }
}