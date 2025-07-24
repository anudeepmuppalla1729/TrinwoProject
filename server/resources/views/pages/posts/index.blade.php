@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <h1 class="text-3xl font-bold mb-8">Latest Blog Posts</h1>
    <div class="space-y-8">
        @forelse($posts as $post)
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-6 flex flex-col">
                @if($post->cover_image)
                    <img src="{{ asset('storage/' . $post->cover_image) }}" alt="Cover Image" class="rounded mb-4 w-full h-64 object-cover">
                @endif
                <h2 class="text-2xl font-semibold mb-2">{{ $post->title }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4 line-clamp-3">{{ Str::limit(strip_tags($post->content), 200) }}</p>
                <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-2">
                    <span>{{ $post->viewCount() }} views</span>
                    <span>{{ ceil(str_word_count(strip_tags($post->content)) / 200) }} min read</span>
                    <span>{{ $post->created_at->diffForHumans() }}</span>
                    <form method="POST" action="{{ route('posts.bookmark', $post->id) }}">
                        @csrf
                        <button type="submit" class="hover:text-blue-600">@if($post->bookmarked) Bookmarked @else Bookmark @endif</button>
                    </form>
                </div>
                <a href="{{ route('posts.show', $post->id) }}" class="mt-2 text-blue-600 hover:underline font-medium">Read More &rarr;</a>
            </div>
        @empty
            <div class="text-center text-gray-500 dark:text-gray-400 py-12">No new blog posts to read. Check back later!</div>
        @endforelse
    </div>
    <div class="mt-8">{{ $posts->links() }}</div>
</div>
@endsection 