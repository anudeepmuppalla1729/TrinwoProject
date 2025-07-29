<?php

namespace App;

use App\Models\Notification;
use App\Models\User;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Follower;
use App\Models\PostVote;
use App\Models\AnswerVote;

class NotificationService
{
    /**
     * Create a welcome notification for new users
     */
    public static function createWelcomeNotification(User $user)
    {
        return Notification::createWelcome($user->user_id);
    }

    /**
     * Create a follower notification
     */
    public static function createFollowerNotification(User $user, User $follower)
    {
        return Notification::createFollower($user->user_id, $follower->user_id);
    }

    /**
     * Create milestone notifications
     */
    public static function createMilestoneNotification(User $user, string $milestoneType, array $data = [])
    {
        return Notification::createMilestone($user->user_id, $milestoneType, $data);
    }

    /**
     * Create upvote notifications
     */
    public static function createUpvoteNotification(User $user, User $sender, $content, string $contentType)
    {
        $contentTitle = '';
        $contentId = '';

        if ($contentType === 'question') {
            $contentTitle = $content->title;
            $contentId = $content->question_id;
        } elseif ($contentType === 'post') {
            $contentTitle = $content->title;
            $contentId = $content->post_id;
        } elseif ($contentType === 'answer') {
            $contentTitle = $content->question->title ?? 'Answer';
            $contentId = $content->answer_id;
        }

        return Notification::createUpvote($user->user_id, $sender->user_id, $contentType, $contentId, $contentTitle);
    }

    /**
     * Create comment notifications
     */
    public static function createCommentNotification(User $user, User $sender, $content, string $contentType, Comment $comment)
    {
        $contentTitle = '';
        $contentId = '';

        if ($contentType === 'question') {
            $contentTitle = $content->title;
            $contentId = $content->question_id;
        } elseif ($contentType === 'post') {
            $contentTitle = $content->title;
            $contentId = $content->post_id;
        }

        return Notification::createComment($user->user_id, $sender->user_id, $contentType, $contentId, $contentTitle, $comment->content);
    }

    /**
     * Create reply notifications
     */
    public static function createReplyNotification(User $user, User $sender, $content, string $contentType, Comment $reply)
    {
        $contentTitle = '';
        $contentId = '';

        if ($contentType === 'question') {
            $contentTitle = $content->title;
            $contentId = $content->question_id;
        } elseif ($contentType === 'post') {
            $contentTitle = $content->title;
            $contentId = $content->post_id;
        }

        return Notification::createReply($user->user_id, $sender->user_id, $contentType, $contentId, $contentTitle, $reply->content);
    }

    /**
     * Create answer notifications
     */
    public static function createAnswerNotification(User $questionOwner, User $answerAuthor, $question, $answer)
    {
        return Notification::createAnswer(
            $questionOwner->user_id, 
            $answerAuthor->user_id, 
            $question->question_id, 
            $question->title, 
            $answer->content
        );
    }

    /**
     * Create report notifications
     */
    public static function createReportNotification(User $user, string $reportType, $report, string $reason)
    {
        return Notification::createReport($user->user_id, $reportType, $report->report_id, $reason);
    }

    /**
     * Create report action notifications
     */
    public static function createReportActionNotification(User $user, string $reportType, $report, string $action, string $adminMessage = null)
    {
        return Notification::createReportAction($user->user_id, $reportType, $report->report_id, $action, $adminMessage);
    }

    /**
     * Check and create milestone notifications
     */
    public static function checkAndCreateMilestones(User $user)
    {
        $milestones = [];

        // Check first question
        $questionCount = $user->questions()->count();
        $hasFirstQuestionMilestone = self::hasMilestoneNotification($user, 'first_question');
        
        \Log::info("User {$user->user_id}: questionCount = {$questionCount}, hasFirstQuestionMilestone = " . ($hasFirstQuestionMilestone ? 'true' : 'false'));
        
        if ($questionCount === 1 && !$hasFirstQuestionMilestone) {
            $milestones[] = 'first_question';
        }

        // Check first answer
        $answerCount = $user->answers()->count();
        $hasFirstAnswerMilestone = self::hasMilestoneNotification($user, 'first_answer');
        
        \Log::info("User {$user->user_id}: answerCount = {$answerCount}, hasFirstAnswerMilestone = " . ($hasFirstAnswerMilestone ? 'true' : 'false'));
        
        if ($answerCount === 1 && !$hasFirstAnswerMilestone) {
            $milestones[] = 'first_answer';
        }

        // Check first upvote
        $upvotes = PostVote::join('posts', 'post_votes.post_id', '=', 'posts.post_id')
            ->where('posts.user_id', $user->user_id)
            ->where('post_votes.vote_type', 'upvote')
            ->count();

        $hasFirstUpvoteMilestone = self::hasMilestoneNotification($user, 'first_upvote');
        
        \Log::info("User {$user->user_id}: upvotes = {$upvotes}, hasFirstUpvoteMilestone = " . ($hasFirstUpvoteMilestone ? 'true' : 'false'));
        
        if ($upvotes === 1 && !$hasFirstUpvoteMilestone) {
            $milestones[] = 'first_upvote';
        }

        // Check reputation milestones (you'll need to implement reputation system)
        // For now, we'll use a simple calculation
        $reputation = $questionCount * 5 + $answerCount * 10 + $upvotes * 2;
        
        if ($reputation >= 100 && $reputation < 200 && !self::hasMilestoneNotification($user, 'reputation_100')) {
            $milestones[] = 'reputation_100';
        } elseif ($reputation >= 500 && $reputation < 600 && !self::hasMilestoneNotification($user, 'reputation_500')) {
            $milestones[] = 'reputation_500';
        } elseif ($reputation >= 1000 && $reputation < 1100 && !self::hasMilestoneNotification($user, 'reputation_1000')) {
            $milestones[] = 'reputation_1000';
        }

        // Check question milestones
        if ($questionCount === 10 && !self::hasMilestoneNotification($user, 'questions_10')) {
            $milestones[] = 'questions_10';
        }

        // Check answer milestones
        if ($answerCount === 25 && !self::hasMilestoneNotification($user, 'answers_25')) {
            $milestones[] = 'answers_25';
        }

        // Check follower milestones
        $followerCount = $user->followers()->count();
        if ($followerCount === 50 && !self::hasMilestoneNotification($user, 'followers_50')) {
            $milestones[] = 'followers_50';
        }

        \Log::info("User {$user->user_id}: Creating milestones: " . implode(', ', $milestones));

        // Create milestone notifications
        foreach ($milestones as $milestone) {
            \Log::info("Creating milestone notification for user {$user->user_id}, type: {$milestone}");
            self::createMilestoneNotification($user, $milestone, [
                'reputation' => $reputation,
                'questions_count' => $questionCount,
                'answers_count' => $answerCount,
                'followers_count' => $followerCount
            ]);
        }
    }

    /**
     * Check if user has already received a specific milestone notification
     */
    private static function hasMilestoneNotification(User $user, string $milestoneType): bool
    {
        $messages = [
            'first_question' => 'Congratulations! You asked your first question.',
            'first_answer' => 'Great job! You provided your first answer.',
            'first_upvote' => 'Awesome! You received your first upvote.',
            'reputation_100' => 'You\'ve reached 100 reputation points!',
            'reputation_500' => 'You\'ve reached 500 reputation points!',
            'reputation_1000' => 'You\'ve reached 1000 reputation points!',
            'questions_10' => 'You\'ve asked 10 questions!',
            'answers_25' => 'You\'ve provided 25 answers!',
            'followers_50' => 'You\'ve reached 50 followers!'
        ];

        $message = $messages[$milestoneType] ?? '';
        
        $exists = $user->notifications()
            ->where('type', 'milestone')
            ->where('message', $message)
            ->exists();
            
        // Log for debugging
        \Log::info("Checking milestone notification for user {$user->user_id}, type: {$milestoneType}, message: {$message}, exists: " . ($exists ? 'true' : 'false'));
        
        return $exists;
    }

    /**
     * Handle upvote notifications
     */
    public static function handleUpvote($content, string $contentType, User $sender)
    {
        $user = null;
        
        if ($contentType === 'question') {
            $user = $content->user;
        } elseif ($contentType === 'post') {
            $user = $content->user;
        } elseif ($contentType === 'answer') {
            $user = $content->user;
        }

        if ($user && $user->user_id !== $sender->user_id) {
            self::createUpvoteNotification($user, $sender, $content, $contentType);
        }
    }

    /**
     * Handle comment notifications
     */
    public static function handleComment($content, string $contentType, Comment $comment, User $sender)
    {
        $user = null;
        
        if ($contentType === 'question') {
            $user = $content->user;
        } elseif ($contentType === 'post') {
            $user = $content->user;
        }

        if ($user && $user->user_id !== $sender->user_id) {
            self::createCommentNotification($user, $sender, $content, $contentType, $comment);
        }
    }

    /**
     * Handle reply notifications
     */
    public static function handleReply($content, string $contentType, Comment $reply, User $sender)
    {
        $user = null;
        
        if ($contentType === 'question') {
            $user = $content->user;
        } elseif ($contentType === 'post') {
            $user = $content->user;
        }

        if ($user && $user->user_id !== $sender->user_id) {
            self::createReplyNotification($user, $sender, $content, $contentType, $reply);
        }
    }

    /**
     * Handle answer notifications
     */
    public static function handleAnswer($question, $answer, User $answerAuthor)
    {
        $questionOwner = $question->user;
        
        // Don't notify if the answer author is the same as the question owner
        if ($questionOwner && $questionOwner->user_id !== $answerAuthor->user_id) {
            self::createAnswerNotification($questionOwner, $answerAuthor, $question, $answer);
        }
    }

    /**
     * Handle follower notifications
     */
    public static function handleFollow(User $user, User $follower)
    {
        if ($user->user_id !== $follower->user_id) {
            self::createFollowerNotification($user, $follower);
        }
    }

    /**
     * Handle report notifications
     */
    public static function handleReport($report, string $reportType, string $reason)
    {
        $user = null;
        
        if ($reportType === 'question') {
            $user = $report->question->user;
        } elseif ($reportType === 'post') {
            $user = $report->post->user;
        } elseif ($reportType === 'answer') {
            $user = $report->answer->user;
        }

        if ($user) {
            self::createReportNotification($user, $reportType, $report, $reason);
        }
    }

    /**
     * Handle report action notifications
     */
    public static function handleReportAction($report, string $reportType, string $action, string $adminMessage = null)
    {
        $user = null;
        
        if ($reportType === 'question') {
            $user = $report->question->user;
        } elseif ($reportType === 'post') {
            $user = $report->post->user;
        } elseif ($reportType === 'answer') {
            $user = $report->answer->user;
        }

        if ($user) {
            self::createReportActionNotification($user, $reportType, $report, $action, $adminMessage);
        }
    }
}
