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
        if ($user->questions()->count() === 1) {
            $milestones[] = 'first_question';
        }

        // Check first answer
        if ($user->answers()->count() === 1) {
            $milestones[] = 'first_answer';
        }

        // Check first upvote
        $upvotes = PostVote::where('post_id', function($query) use ($user) {
            $query->select('post_id')->from('posts')->where('user_id', $user->user_id);
        })->where('vote_type', 'upvote')->count();

        if ($upvotes === 1) {
            $milestones[] = 'first_upvote';
        }

        // Check reputation milestones (you'll need to implement reputation system)
        // For now, we'll use a simple calculation
        $reputation = $user->questions()->count() * 5 + $user->answers()->count() * 10 + $upvotes * 2;
        
        if ($reputation >= 100 && $reputation < 200) {
            $milestones[] = 'reputation_100';
        } elseif ($reputation >= 500 && $reputation < 600) {
            $milestones[] = 'reputation_500';
        } elseif ($reputation >= 1000 && $reputation < 1100) {
            $milestones[] = 'reputation_1000';
        }

        // Check question milestones
        $questionCount = $user->questions()->count();
        if ($questionCount === 10) {
            $milestones[] = 'questions_10';
        }

        // Check answer milestones
        $answerCount = $user->answers()->count();
        if ($answerCount === 25) {
            $milestones[] = 'answers_25';
        }

        // Check follower milestones
        $followerCount = $user->followers()->count();
        if ($followerCount === 50) {
            $milestones[] = 'followers_50';
        }

        // Create milestone notifications
        foreach ($milestones as $milestone) {
            self::createMilestoneNotification($user, $milestone, [
                'reputation' => $reputation,
                'questions_count' => $questionCount,
                'answers_count' => $answerCount,
                'followers_count' => $followerCount
            ]);
        }
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
