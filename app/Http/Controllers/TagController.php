<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Http\Resources\TagResource;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::all();
        return TagResource::collection($tags);
    }

    public function store(Request $request)
    {
        $tag = Tag::create($request->all());
        return new TagResource($tag);
    }

    public function show($id)
    {
        $tag = Tag::find($id);
        if ($tag) {
            return new TagResource($tag);
        } else {
            return response()->json(['message' => 'Tag not found'], 404);
        }
    }


    public function update(Request $request, $id)
    {
        $tag = Tag::find($id);
        if ($tag) {
            $tag->update($request->all());
            return new TagResource($tag);
        } else {
            return response()->json(['message' => 'Tag not found'], 404);
        }
    }

    public function destroy($id)
    {
        $tag = Tag::find($id);
        if ($tag) {
            $tag->delete();
            return response()->json(['message' => 'Tag deleted'], 200);
        } else {
            return response()->json(['message' => 'Tag not found'], 404);
        }
    }
}