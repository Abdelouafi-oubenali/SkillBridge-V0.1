<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        return Course::with(['category', 'tags'])->get();
    }

    public function show($id)
    {
        return Course::with(['category', 'tags'])->findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array', 
            'tags.*' => 'exists:tags,id', 
        ]);

        $course = Course::create($request->only(['title', 'description', 'category_id']));


        if ($request->has('tags')) {
            $course->tags()->attach($request->tags);
        }

        return $course;
    }

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

        if ($request->has('tags')) {
            $course->tags()->sync($request->tags);
        }

        return $course;
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return response()->noContent();
    }
}
