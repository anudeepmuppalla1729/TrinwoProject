<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostImage;
use App\Models\PostBookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the posts.
     */
    public function index()
    {
        $posts = Post::with(['user', 'images'])
            ->where('visibility', 'public')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('pages.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create()
    {
        return view('pages.posts.create');
    }

    /**
     * Store a newly created post in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'visibility' => 'required|in:public,private',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'visibility' => $request->visibility,
        ];

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('posts', 's3');
        }

        $post = Post::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Blog post created successfully!',
            'post_id' => $post->post_id
        ]);
    }

    /**
     * Display the specified post.
     */
    public function show($id)
    {
        $post = Post::with(['user', 'images', 'comments.user', 'userSeenPosts'])
            ->findOrFail($id);
        $user = Auth::user();

        // Check if post is private and not owned by current user
        if ($post->visibility === 'private' && $post->user_id !== $user->user_id) {
            abort(403, 'You do not have permission to view this post.');
        }

        // Mark as seen if not already
        if (!$post->userSeenPosts->where('user_id', $user->user_id)->count()) {
            \App\Models\UserSeenPost::create([
                'user_id' => $user->user_id,
                'post_id' => $post->post_id,
                'created_at' => now(),
            ]);
        }

        // Pass all necessary data to the view
        return view('pages.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit($id)
    {
        $post = Post::with('images')->findOrFail($id);
        
        // Check if user is authorized to edit this post
        if ($post->user_id !== Auth::id()) {
            abort(403, 'You do not have permission to edit this post.');
        }
        
        return view('pages.posts.edit', compact('post'));
    }

    /**
     * Update the specified post in storage.
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        
        // Check if user is authorized to update this post
        if ($post->user_id !== Auth::id()) {
            abort(403, 'You do not have permission to update this post.');
        }
        
        $request->validate([
            'heading' => 'required|string|max:255',
            'details' => 'required|string',
            'visibility' => 'required|in:public,private',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $post->update([
            'heading' => $request->heading,
            'details' => $request->details,
            'visibility' => $request->visibility,
        ]);
        
        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 's3');
            
            PostImage::create([
                'post_id' => $post->post_id,
                'image_url' => $imagePath,
            ]);
        }
        
        return redirect()->route('posts.show', $post->post_id)
            ->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        
        // Check if user is authorized to delete this post
        if ($post->user_id !== Auth::id()) {
            abort(403, 'You do not have permission to delete this post.');
        }
        
        $post->delete();
        
        return redirect()->route('profile.posts')
            ->with('success', 'Post deleted successfully!');
    }
    
    /**
     * Store a post via AJAX request (for the insight modal).
     */
    public function storeAjax(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'visibility' => 'required|in:public,private',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'visibility' => $request->visibility,
        ];

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('posts', 's3');
        }

        $post = Post::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Blog post created successfully!',
            'post_id' => $post->post_id
        ]);
    }

    /**
     * Get posts for dashboard in JSON format
     */
    public function getDashboardPosts()
    {
        $userId = Auth::id();
        $posts = Post::with(['user', 'userSeenPosts'])
            ->where('visibility', 'public')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($post) {
                $coverImage = null;
                if ($post->cover_image) {
                    if (Str::startsWith($post->cover_image, ['http://', 'https://'])) {
                        $coverImage = $post->cover_image;
                    } else {
                        $coverImage = Storage::disk('s3')->url($post->cover_image);
                    }
                }
                return [
                    'post_id' => $post->post_id,
                    'user_id' => $post->user->user_id,
                    'profileName' => $post->user->name,
                    'avatar' => $post->user->avatar_url,
                    'title' => $post->title,
                    'content' => $post->content,
                    'cover_image' => $coverImage,
                    'created_at' => $post->created_at->format('M d, Y'),
                    'views' => $post->userSeenPosts->count(),
                    // Add more fields as needed for bookmarks, etc.
                ];
            });
        return response()->json($posts);
    }
    
    /**
     * Upvote a post
     */
    public function upvote($id)
    {
        $post = Post::findOrFail($id);
        $userId = Auth::id();
        
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }
        
        // Check if user has already voted on this post
        $existingVote = $post->getUserVote($userId);
        
        if ($existingVote) {
            // If user already upvoted, remove the vote (toggle off)
            if ($existingVote->vote_type === 'upvote') {
                $existingVote->delete();
                $post->decrement('upvotes');
                
                return response()->json([
                    'success' => true,
                    'message' => 'Upvote removed',
                    'upvotes' => $post->upvotes,
                    'downvotes' => $post->downvotes,
                    'userVote' => null
                ]);
            }
            
            // If user previously downvoted, change to upvote
            $existingVote->update(['vote_type' => 'upvote']);
            $post->decrement('downvotes');
            $post->increment('upvotes');
            
            return response()->json([
                'success' => true,
                'message' => 'Changed from downvote to upvote',
                'upvotes' => $post->upvotes,
                'downvotes' => $post->downvotes,
                'userVote' => 'upvote'
            ]);
        }
        
        // Create new upvote
        $post->votes()->create([
            'user_id' => $userId,
            'vote_type' => 'upvote'
        ]);
        
        $post->increment('upvotes');
        
        return response()->json([
            'success' => true,
            'message' => 'Post upvoted successfully',
            'upvotes' => $post->upvotes,
            'downvotes' => $post->downvotes,
            'userVote' => 'upvote'
        ]);
    }
    
    /**
     * Downvote a post
     */
    public function downvote($id)
    {
        $post = Post::findOrFail($id);
        $userId = Auth::id();
        
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }
        
        // Check if user has already voted on this post
        $existingVote = $post->getUserVote($userId);
        
        if ($existingVote) {
            // If user already downvoted, remove the vote (toggle off)
            if ($existingVote->vote_type === 'downvote') {
                $existingVote->delete();
                $post->decrement('downvotes');
                
                return response()->json([
                    'success' => true,
                    'message' => 'Downvote removed',
                    'upvotes' => $post->upvotes,
                    'downvotes' => $post->downvotes,
                    'userVote' => null
                ]);
            }
            
            // If user previously upvoted, change to downvote
            $existingVote->update(['vote_type' => 'downvote']);
            $post->decrement('upvotes');
            $post->increment('downvotes');
            
            return response()->json([
                'success' => true,
                'message' => 'Changed from upvote to downvote',
                'upvotes' => $post->upvotes,
                'downvotes' => $post->downvotes,
                'userVote' => 'downvote'
            ]);
        }
        
        // Create new downvote
        $post->votes()->create([
            'user_id' => $userId,
            'vote_type' => 'downvote'
        ]);
        
        $post->increment('downvotes');
        
        return response()->json([
            'success' => true,
            'message' => 'Post downvoted successfully',
            'upvotes' => $post->upvotes,
            'downvotes' => $post->downvotes,
            'userVote' => 'downvote'
        ]);
    }
    
    /**
     * Get the current user's vote status for a post
     */
    public function getUserVoteStatus($id)
    {
        $post = Post::findOrFail($id);
        $userId = Auth::id();
        
        if (!$userId) {
            return response()->json([
                'success' => true,
                'userVote' => null
            ]);
        }
        
        $vote = $post->getUserVote($userId);
        
        return response()->json([
            'success' => true,
            'userVote' => $vote ? $vote->vote_type : null
        ]);
    }
    
    /**
     * Bookmark or unbookmark a post
     */
    public function bookmark($id)
    {
        $post = Post::findOrFail($id);
        $userId = Auth::id();
        
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }
        
        // Check if user has already bookmarked this post
        $existingBookmark = $post->getUserBookmark($userId);
        
        if ($existingBookmark) {
            // If already bookmarked, remove the bookmark (toggle off)
            $existingBookmark->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Bookmark removed',
                'isBookmarked' => false
            ]);
        }
        
        // Create new bookmark
        PostBookmark::create([
            'user_id' => $userId,
            'post_id' => $post->post_id
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Post bookmarked successfully',
            'isBookmarked' => true
        ]);
    }
    
    /**
     * Get the current user's bookmark status for a post
     */
    public function getUserBookmarkStatus($id)
    {
        $post = Post::findOrFail($id);
        $userId = Auth::id();
        
        if (!$userId) {
            return response()->json([
                'success' => true,
                'isBookmarked' => false
            ]);
        }
        
        $bookmark = $post->getUserBookmark($userId);
        
        return response()->json([
            'success' => true,
            'isBookmarked' => $bookmark ? true : false
        ]);
    }

    /**
     * Report a post
     */
    public function report(Request $request, $id)
    {
        $user = $request->user();
        if (!$user) {
            return back()->with('error', 'You must be logged in to report.');
        }
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);
        $userId = AUTH::id();
        $postId = $id;
        // Prevent duplicate reports by same user
        $existing = \App\Models\PostReport::where('reporter_id', $userId)->where('post_id', $postId)->first();
        if ($existing) {
            $msg = 'You have already reported this post.';
            if ($request->expectsJson()) return response()->json(['success' => false, 'message' => $msg], 409);
            return back()->with('error', $msg);
        }
        \App\Models\PostReport::create([
            'reporter_id' => $userId,
            'post_id' => $postId,
            'reason' => $request->reason,
        ]);
        $msg = 'Post reported successfully.';
        if ($request->expectsJson()) return response()->json(['success' => true, 'message' => $msg]);
        return back()->with('success', $msg);
    }
}