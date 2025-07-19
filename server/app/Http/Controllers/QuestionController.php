<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Answer;
use App\Models\Tag;
use App\Models\QuestionTag;
use App\Models\QuestionBookmark;
use App\Models\User;
use App\Models\QuestionReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    /**
     * Display a listing of the questions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get the current user ID if authenticated
        $userId = Auth::id();
        
        // Fetch questions from the database
        $questions = Question::with(['user', 'tags'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($question) use ($userId) {
                // Check if the question is bookmarked by the current user
                $isBookmarked = false;
                if ($userId) {
                    $isBookmarked = QuestionBookmark::where('user_id', $userId)
                        ->where('question_id', $question->question_id)
                        ->exists();
                }
                
                return [
                    'id' => $question->question_id,
                    'title' => $question->title,
                    'excerpt' => substr($question->description, 0, 200) . (strlen($question->description) > 200 ? '...' : ''),
                    'user' => $question->user->name,
                    'user_id' => $question->user->user_id,
                    'created_at' => $question->created_at->diffForHumans(),
                    'answers' => $question->answers->count(),
                    'upvotes' => 0, // Questions don't have upvotes in this system
                    'downvotes' => 0, // Questions don't have downvotes in this system
                    'tags' => $question->tags->pluck('name')->toArray(),
                    'is_bookmarked' => $isBookmarked
                ];
            });
            
        return view('pages.questions', compact('questions'));
    }

    /**
     * Show the form for creating a new question.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // This would show a form to create a new question
        // For now, we'll redirect to the questions page
        return redirect()->route('questions');
    }

    /**
     * Store a newly created question in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // Validate the request
            $validator = \Illuminate\Support\Facades\Validator::make([
                'title' => $request->title,
                'description' => $request->description,
                'tags' => $request->tags,
                'privacy' => $request->privacy,
            ], [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'tags' => 'nullable|string',
                'privacy' => 'required|in:Public,Private',
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors()
                    ], 422);
                }
                
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Create the question
            $question = new Question();
            $question->user_id = Auth::id();
            $question->title = $request->title;
            $question->description = $request->description;
            $question->visibility = strtolower($request->privacy);
            $question->is_closed = false;
            $question->save();

            // Handle tags if provided
            if ($request->has('tags') && !empty($request->tags)) {
                $tagNames = explode(',', $request->tags);
                
                foreach ($tagNames as $tagName) {
                    $tagName = trim($tagName);
                    if (empty($tagName)) continue;
                    
                    // Find or create the tag
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    
                    // Associate the tag with the question
                    $question->tags()->attach($tag->tag_id);
                }
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Question posted successfully!',
                    'question' => $question
                ]);
            }

            return redirect()->route('questions')->with('success', 'Question posted successfully!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while posting your question: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'An error occurred while posting your question: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified question.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Fetch the question from the database
        $questionModel = Question::with(['user', 'tags', 'answers.user'])
            ->findOrFail($id);
            
        // Check if the question is bookmarked by the current user
        $isBookmarked = false;
        if (Auth::check()) {
            $isBookmarked = QuestionBookmark::where('user_id', Auth::id())
                ->where('question_id', $id)
                ->exists();
        }
            
        // Format the question data
        $question = [
            'id' => $questionModel->question_id,
            'title' => $questionModel->title,
            'description' => $questionModel->description,
            'user' => $questionModel->user->name,
            'user_id' => $questionModel->user->user_id,
            'user_location' => $questionModel->user->studying_in ?? 'Unknown Location',
            'created_at' => $questionModel->created_at->diffForHumans(),
            'upvotes' => 0, // Questions don't have upvotes in this system
            'downvotes' => 0, // Questions don't have downvotes in this system
            'tags' => $questionModel->tags->pluck('name')->toArray(),
            'is_bookmarked' => $isBookmarked
        ];
        
        // Format the answers data
        $answers = $questionModel->answers->map(function($answer) {
            return [
                'id' => $answer->answer_id,
                'content' => $answer->content,
                'user' => $answer->user->name,
                'user_id' => $answer->user->user_id,
                'created_at' => $answer->created_at->diffForHumans(),
                'upvotes' => $answer->getUpvotesCount(),
                'downvotes' => $answer->getDownvotesCount(),
                'is_accepted' => $answer->is_accepted ?? false
            ];
        });
        
        return view('pages.question', compact('question', 'answers'));
    }

    /**
     * Show the form for editing the specified question.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // This would show a form to edit an existing question
        // For now, we'll redirect to the question page
        return redirect()->route('question', ['id' => $id]);
    }

    /**
     * Update the specified question in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'tags' => 'nullable|string',
        ]);

        // In a real application, you would update the question in the database
        // For now, we'll redirect to the question page
        return redirect()->route('question', ['id' => $id])->with('success', 'Question updated successfully!');
    }

    /**
     * Remove the specified question from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // Find the question
            $question = Question::findOrFail($id);
            
            // Check if the authenticated user is the owner of the question
            if ($question->user_id !== Auth::id()) {
                return redirect()->back()->with('error', 'You do not have permission to delete this question.');
            }
            
            // Begin transaction to ensure all related data is deleted properly
            \DB::beginTransaction();
            
            // Delete related answer votes
            foreach ($question->answers as $answer) {
                $answer->votes()->delete();
            }
            
            // Delete related answers
            $question->answers()->delete();
            
            // Delete related bookmarks
            QuestionBookmark::where('question_id', $id)->delete();
            
            // Delete tag associations (the pivot table entries)
            $question->tags()->detach();
            
            // Finally delete the question itself
            $question->delete();
            
            // Commit the transaction
            \DB::commit();
            
            return redirect()->route('profile.questions')->with('success', 'Question deleted successfully!');
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            \DB::rollBack();
            
            return redirect()->back()->with('error', 'An error occurred while deleting the question: ' . $e->getMessage());
        }
    }

    /**
     * Upvote a question.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function upvote($id)
    {
        // In a real application, you would upvote the question in the database
        // For now, we'll redirect to the question page
        return redirect()->route('question', ['id' => $id])->with('success', 'Question upvoted!');
    }

    /**
     * Downvote a question.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function downvote($id)
    {
        // In a real application, you would downvote the question in the database
        // For now, we'll redirect to the question page
        return redirect()->route('question', ['id' => $id])->with('success', 'Question downvoted!');
    }

    /**
     * Bookmark a question.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bookmark($id)
    {
        try {
            $userId = Auth::id();
            $questionId = $id;
            
            // Check if the question exists
            $question = Question::findOrFail($questionId);
            
            // Check if the question is already bookmarked by the user
            $bookmark = QuestionBookmark::where('user_id', $userId)
                ->where('question_id', $questionId)
                ->first();
                
            if ($bookmark) {
                // If already bookmarked, remove the bookmark
                $bookmark->delete();
                $message = 'Bookmark removed!';
                $isBookmarked = false;
            } else {
                // If not bookmarked, add a bookmark
                QuestionBookmark::create([
                    'user_id' => $userId,
                    'question_id' => $questionId
                ]);
                $message = 'Question bookmarked!';
                $isBookmarked = true;
            }
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'isBookmarked' => $isBookmarked
                ]);
            }
            
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Share a question.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function share($id)
    {
        // In a real application, you would generate a share link for the question
        // For now, we'll redirect to the question page
        return redirect()->route('question', ['id' => $id])->with('success', 'Question shared!');
    }

    /**
     * Report a question
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
        $questionId = $id;
        // Prevent duplicate reports by same user
        $existing = \App\Models\QuestionReport::where('reporter_id', $userId)->where('question_id', $questionId)->first();
        if ($existing) {
            $msg = 'You have already reported this question.';
            if ($request->expectsJson()) return response()->json(['success' => false, 'message' => $msg], 409);
            return back()->with('error', $msg);
        }
        \App\Models\QuestionReport::create([
            'reporter_id' => $userId,
            'question_id' => $questionId,
            'reason' => $request->reason,
        ]);
        $msg = 'Question reported successfully.';
        if ($request->expectsJson()) return response()->json(['success' => true, 'message' => $msg]);
        return back()->with('success', $msg);
    }
}