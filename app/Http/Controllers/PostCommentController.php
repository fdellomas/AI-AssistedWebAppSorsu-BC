<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostComment;

class PostCommentController extends Controller
{
    public function comment(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'user_id' => 'required|exists:users,id',
            'comment' => 'required',
        ]);

        $comment = PostComment::create($validated);

        return response()->json([
            'comment' => $comment
        ]);
    }
}
