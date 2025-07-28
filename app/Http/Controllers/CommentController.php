<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, $postId)
    {
        $request->validate([
            'comment_text' => 'required|string|max:1000',
        ]);

        // Check if post exists
        $post = Post::findOrFail($postId);

        $comment = Comment::create([
            'post_id' => $postId,
            'user_id' => Auth::id(),
            'comment_text' => $request->comment_text,
        ]);

        // Send comment notification to post author (if not self)
        if ($post->user_id !== Auth::id()) {
            \App\NotificationService::createCommentNotification($post->user, Auth::user(), $post, 'post', $comment);
        }

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully!',
            'comment' => [
                'id' => $comment->comment_id,
                'text' => $comment->comment_text,
                'user' => Auth::user()->name,
                'created_at' => $comment->created_at->format('M d, Y'),
                'is_owner' => true // Always true for newly created comments by the current user
            ]
        ]);
    }

    /**
     * Update the specified comment in storage.
     */
    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        
        // Check if user is authorized to update this comment
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to update this comment.'
            ], 403);
        }
        
        $request->validate([
            'comment_text' => 'required|string|max:1000',
        ]);
        
        $comment->update([
            'comment_text' => $request->comment_text,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Comment updated successfully!',
            'comment' => [
                'id' => $comment->comment_id,
                'text' => $comment->comment_text,
                'user' => Auth::user()->name,
                'created_at' => $comment->created_at->format('M d, Y')
            ]
        ]);
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        
        // Check if user is authorized to delete this comment
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete this comment.'
            ], 403);
        }
        
        $comment->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully!'
        ]);
    }
}