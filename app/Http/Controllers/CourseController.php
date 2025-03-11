<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // Lister tous les cours
    public function index()
    {
        return Course::with(['category', 'tags'])->get();
    }

    // Afficher un cours spécifique
    public function show($id)
    {
        return Course::with(['category', 'tags'])->findOrFail($id);
    }

    // Créer un nouveau cours
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array', // Liste des IDs des tags
            'tags.*' => 'exists:tags,id', // Vérifie que chaque tag existe
        ]);

        $course = Course::create($request->only(['title', 'description', 'category_id']));

        // Associer les tags au cours
        if ($request->has('tags')) {
            $course->tags()->attach($request->tags);
        }

        return $course;
    }

    // Mettre à jour un cours existant
    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array', 
            'tags.*' => 'exists:tags,id',
        ]);

        $course->update($request->only(['title', 'description', 'category_id']));

        // Synchroniser les tags du cours
        if ($request->has('tags')) {
            $course->tags()->sync($request->tags);
        }

        return $course;
    }

    // Supprimer un cours
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return response()->noContent();
    }
}
