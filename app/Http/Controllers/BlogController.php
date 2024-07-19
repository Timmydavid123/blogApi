<?php

namespace App\Http\Controllers;

use App\Models\Blog; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends Controller {
    public function index() {
        return response()->json(Blog::all(), 200);
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|url'
        ]);

        $blog = Blog::create($request->all());
        $blog->save();
        return response()->json($blog, 201);
    }

    public function show($id) {
        $blog = Blog::with('posts')->findOrFail($id);
        return response()->json($blog, 200);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'image' => 'nullable|url'
        ]);

        $blog = Blog::findOrFail($id);
        $blog->update($request->all());
        return response()->json($blog, 200);
    }

    public function destroy($id) {
        $blog = Blog::findOrFail($id);
        $blog->delete();
        return response()->json(null, 204);
    }
}