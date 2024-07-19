<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;       
use App\Models\Like;       
use App\Models\Comment;    

class PostController extends Controller {
    public function index($blogId) {
        return response()->json(Post::where('blog_id', $blogId)->get(), 200);
    }

    public function store(Request $request, $blogId) {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|url'
        ]);

        $post = new Post($request->all());
        $post->blog_id = $blogId;
        $post->save();
        return response()->json($post, 201);
    }

    public function show($blogId, $postId) {
        $post = Post::with(['comments', 'likes'])->where('blog_id', $blogId)->findOrFail($postId);
        return response()->json($post, 200);
    }

    public function update(Request $request, $blogId, $postId) {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'image' => 'nullable|url'
        ]);

        $post = Post::where('blog_id', $blogId)->findOrFail($postId);
        $post->update($request->all());
        return response()->json($post, 200);
    }

    public function destroy($blogId, $postId) {
        $post = Post::where('blog_id', $blogId)->findOrFail($postId);
        $post->delete();
        return response()->json(null, 204);
    }

    public function likePost($postId) {
        $like = new Like();
        $like->post_id = $postId;
        $like->user_id = auth()->user()->id;
        $like->save();
        return response()->json($like, 201);
    }

    public function commentOnPost(Request $request, $postId) {
        $request->validate([
            'comment' => 'required|string'
        ]);

        $comment = new Comment();
        $comment->post_id = $postId;
        $comment->user_id = auth()->user()->id;
        $comment->comment = $request->comment;
        $comment->save();
        return response()->json($comment, 201);
    }
}
