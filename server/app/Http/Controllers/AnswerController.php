<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        
        // Create the answer
        $answer = new Answer();
        $answer->question_id = $questionId;
        $answer->user_id = Auth::id();
        $answer->content = $request->content;
        $answer->save();
        
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
        // In a real application, you would delete the answer from the database
        // For now, we'll redirect to the question page
        return redirect()->back()->with('success', 'Answer deleted successfully!');
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
        // In a real application, you would mark the answer as accepted in the database
        // For now, we'll redirect to the question page
        return redirect()->back()->with('success', 'Answer accepted!');
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
}