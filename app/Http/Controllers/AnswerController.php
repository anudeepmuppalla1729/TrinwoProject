<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnswerController extends Controller
{
    /**
     * Store a newly created answer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $questionId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $questionId)
    {
        // Validate the request
        $request->validate([
            'content' => 'required|string',
        ]);

        // Check if the question exists
        $question = Question::findOrFail($questionId);
        
        // Check if the question is closed
        if ($question->is_closed) {
            return redirect()->route('question', ['id' => $questionId])->with('error', 'This question is closed and no longer accepts answers.');
        }
        
        // Create the answer
        $answer = Answer::create([
            'question_id' => $questionId,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        // Check and create milestone notifications
        \App\NotificationService::checkAndCreateMilestones(Auth::user());
        
        return redirect()->route('question', ['id' => $questionId])->with('success', 'Answer posted successfully!');
    }

    /**
     * Update the specified answer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'content' => 'required|string',
        ]);

        // In a real application, you would update the answer in the database
        // For now, we'll redirect to the question page
        return redirect()->back()->with('success', 'Answer updated successfully!');
    }

    /**
     * Remove the specified answer from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // Find the answer
            $answer = Answer::findOrFail($id);
            
            // Check if the authenticated user is the owner of the answer
            if ($answer->user_id !== Auth::id()) {
                return redirect()->back()->with('error', 'You do not have permission to delete this answer.');
            }
            
            // Begin transaction to ensure all related data is deleted properly
            \DB::beginTransaction();
            
            // The boot method in the Answer model will handle deleting related votes and reports
            $answer->delete();
            
            // Commit the transaction
            \DB::commit();
            
            return redirect()->back()->with('success', 'Answer deleted successfully!');
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            \DB::rollBack();
            
            return redirect()->back()->with('error', 'An error occurred while deleting the answer: ' . $e->getMessage());
        }
    }

    /**
     * Upvote an answer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function upvote($id)
    {
        $answer = Answer::findOrFail($id);
        $userId = Auth::id();
        
        if (!$userId) {
            return redirect()->back()->with('error', 'You must be logged in to vote!');
        }
        
        // Check if user has already voted on this answer
        $existingVote = $answer->getUserVote($userId);
        
        if ($existingVote) {
            // If user already upvoted, remove the vote (toggle off)
            if ($existingVote->vote_type === 'upvote') {
                $existingVote->delete();
                
                return redirect()->back()->with('success', 'Upvote removed!');
            }
            
            // If user previously downvoted, change to upvote
            $existingVote->update(['vote_type' => 'upvote']);
            
            return redirect()->back()->with('success', 'Changed from downvote to upvote!');
        }
        
        // Create new upvote
        $answer->votes()->create([
            'user_id' => $userId,
            'vote_type' => 'upvote'
        ]);
        
        return redirect()->back()->with('success', 'Answer upvoted!');
    }

    /**
     * Downvote an answer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function downvote($id)
    {
        $answer = Answer::findOrFail($id);
        $userId = Auth::id();
        
        if (!$userId) {
            return redirect()->back()->with('error', 'You must be logged in to vote!');
        }
        
        // Check if user has already voted on this answer
        $existingVote = $answer->getUserVote($userId);
        
        if ($existingVote) {
            // If user already downvoted, remove the vote (toggle off)
            if ($existingVote->vote_type === 'downvote') {
                $existingVote->delete();
                
                return redirect()->back()->with('success', 'Downvote removed!');
            }
            
            // If user previously upvoted, change to downvote
            $existingVote->update(['vote_type' => 'downvote']);
            
            return redirect()->back()->with('success', 'Changed from upvote to downvote!');
        }
        
        // Create new downvote
        $answer->votes()->create([
            'user_id' => $userId,
            'vote_type' => 'downvote'
        ]);
        
        return redirect()->back()->with('success', 'Answer downvoted!');
    }

    /**
     * Mark an answer as accepted.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function accept($id)
    {
        $answer = \App\Models\Answer::findOrFail($id);
        $question = $answer->question;
        
        // Check if the current user is the question author
        if (auth()->id() !== $question->user_id) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Only the question author can accept answers'], 403);
            }
            return redirect()->back()->with('error', 'Only the question author can accept answers');
        }
        
        // Update the question's accepted_answer_id
        $question->accepted_answer_id = $answer->answer_id;
        $question->save();
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Answer accepted successfully!',
                'answer_id' => $answer->answer_id
            ]);
        }
        
        return redirect()->back()->with('success', 'Answer accepted successfully!');
    }

    /**
     * Comment on an answer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function comment(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'content' => 'required|string',
        ]);

        // In a real application, you would store the comment in the database
        // For now, we'll redirect to the question page
        return redirect()->back()->with('success', 'Comment posted successfully!');
    }

    /**
     * Share an answer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function share($id)
    {
        // In a real application, you would generate a share link for the answer
        // For now, we'll redirect to the question page
        return redirect()->back()->with('success', 'Answer shared!');
    }

    /**
     * Report an answer
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
        $userId = $user->user_id;
        $answerId = $id;
        // Prevent duplicate reports by same user
        $existing = \App\Models\AnswerReport::where('reporter_id', $userId)->where('answer_id', $answerId)->first();
        if ($existing) {
            $msg = 'You have already reported this answer.';
            if ($request->expectsJson()) return response()->json(['success' => false, 'message' => $msg], 409);
            return back()->with('error', $msg);
        }
        \App\Models\AnswerReport::create([
            'reporter_id' => $userId,
            'answer_id' => $answerId,
            'reason' => $request->reason,
        ]);
        $msg = 'Answer reported successfully.';
        if ($request->expectsJson()) return response()->json(['success' => true, 'message' => $msg]);
        return back()->with('success', $msg);
    }
}