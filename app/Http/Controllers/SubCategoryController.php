<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    // Lister toutes les sous-catégories d'une catégorie parente
    public function index($parentId)
    {
        $parentCategory = Category::findOrFail($parentId);
        return $parentCategory->subCategories;
    }

    // Créer une nouvelle sous-catégorie pour une catégorie parente
    public function store(Request $request, $parentId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $parentCategory = Category::findOrFail($parentId);

        $subCategory = new Category([
            'name' => $request->name,
            'parent_id' => $parentId,
        ]);

        $parentCategory->subCategories()->save($subCategory);

        return $subCategory;
    }

    // Afficher une sous-catégorie spécifique
    public function show($parentId, $id)
    {
        return Category::where('parent_id', $parentId)->findOrFail($id);
    }

    // Mettre à jour une sous-catégorie
    public function update(Request $request, $parentId, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $subCategory = Category::where('parent_id', $parentId)->findOrFail($id);
        $subCategory->update($request->all());

        return $subCategory;
    }

    // Supprimer une sous-catégorie
    public function destroy($parentId, $id)
    {
        $subCategory = Category::where('parent_id', $parentId)->findOrFail($id);
        $subCategory->delete();

        return response()->noContent();
    }
}
