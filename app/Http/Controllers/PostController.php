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

    public function show(Post $post)
    {
        return response()->json([
            'message' => 'OK',
            'post' => $post
        ], 200);
    }

    public function update(Post $post, Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'message' => 'required',
            'images' => 'nullable|array',
            'images.*' => 'nullable',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable',
        ]);
        $post->update($validated);
        // if ($request->has('images')) {
        //     foreach ($request->input('images') as $image) {
        //         $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        //         $path = 'posts/' . $filename;
        //         $image->storeAs('/public/posts', $filename);
        //         $imgPaths[] = $path;
        //     }
        //     $post->update([
        //         'attachments' => $imgPaths
        //     ]);
        // }
        if (count($validated['images'])>0) {
            $imgPaths = [];
            foreach ($request->input('images') as $base64Image) {
                list($type, $data) = explode(';', $base64Image);
                list(, $data)      = explode(',', $data);
    
                $decodedImage = base64_decode($data);
                $extension = 'gif';
                if (strpos($type, 'jpeg') !== false) {
                    $extension = 'jpg';
                } elseif (strpos($type, 'png') !== false) {
                    $extension = 'png';
                }
    
                $filename = time() . '_' . uniqid() . '.' . $extension;
    
                $path = storage_path('app/public/posts/' . $filename);
    
                file_put_contents($path, $decodedImage);
    
                $imgPaths[] = 'posts/' . $filename;
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

    public function delete($id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        $result = $post->delete();
        if (!$result) {
            return response()->json([
                'message' => 'Failed to delete post'
            ], 400);
        }
        return response()->json([
            'message' => 'OK'
        ], 200);
    }
}
