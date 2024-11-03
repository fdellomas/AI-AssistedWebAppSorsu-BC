<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderByDesc('created_at')
            ->get();
        
        return response()->json([
            'post' => $posts
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required',
            'title' => 'required',
            'images' => 'nullable|array',
            'images.*' => 'nullable|file',
        ]);

        $post = Post::create($validated);
        $imgPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = 'posts/' . $filename;
                $image->storeAs('/public/posts', $filename);
                $imgPaths[] = $path;
            }
            $post->update([
                'attachments' => $imgPaths
            ]);
        }

        return response()->json([
            'message' => 'OK',
            'post' => $post
        ], 200);
    }
}
