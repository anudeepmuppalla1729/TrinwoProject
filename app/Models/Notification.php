<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sender_id',
        'type',
        'title',
        'message',
        'link',
        'data',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime'
    ];

    // Notification types
    const TYPE_WELCOME = 'welcome';
    const TYPE_FOLLOWER = 'follower';
    const TYPE_MILESTONE = 'milestone';
    const TYPE_UPVOTE = 'upvote';
    const TYPE_COMMENT = 'comment';
    const TYPE_REPLY = 'reply';
    const TYPE_ANSWER = 'answer';
    const TYPE_REPORT = 'report';
    const TYPE_REPORT_ACTION = 'report_action';

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'user_id');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Helper methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null
        ]);
    }

    public function getIcon()
    {
        return match($this->type) {
            self::TYPE_WELCOME => 'fas fa-star',
            self::TYPE_FOLLOWER => 'fas fa-user-plus',
            self::TYPE_MILESTONE => 'fas fa-trophy',
            self::TYPE_UPVOTE => 'fas fa-thumbs-up',
            self::TYPE_COMMENT => 'fas fa-comment',
            self::TYPE_REPLY => 'fas fa-reply',
            self::TYPE_ANSWER => 'fas fa-lightbulb',
            self::TYPE_REPORT => 'fas fa-flag',
            self::TYPE_REPORT_ACTION => 'fas fa-check-circle',
            default => 'fas fa-bell'
        };
    }

    public function getColor()
    {
        return match($this->type) {
            self::TYPE_WELCOME => 'text-warning',
            self::TYPE_FOLLOWER => 'text-primary',
            self::TYPE_MILESTONE => 'text-success',
            self::TYPE_UPVOTE => 'text-info',
            self::TYPE_COMMENT, self::TYPE_REPLY, self::TYPE_ANSWER => 'text-secondary',
            self::TYPE_REPORT => 'text-danger',
            self::TYPE_REPORT_ACTION => 'text-success',
            default => 'text-muted'
        };
    }

    // Static methods for creating notifications
    public static function createWelcome($userId)
    {
        return self::create([
            'user_id' => $userId,
            'type' => self::TYPE_WELCOME,
            'title' => 'Welcome to Inqube!',
            'message' => 'Welcome to Inqube! We\'re excited to have you join our community. Start exploring questions, sharing knowledge, and connecting with other users.',
            'link' => route('dashboard', false)
        ]);
    }

    public static function createFollower($userId, $followerId)
    {
        $follower = User::find($followerId);
        return self::create([
            'user_id' => $userId,
            'sender_id' => $followerId,
            'type' => self::TYPE_FOLLOWER,
            'title' => 'New Follower',
            'message' => $follower->name . ' started following you',
            'link' => route('user.profile', $follower->user_id, false),
            'data' => [
                'follower_name' => $follower->name,
                'follower_username' => $follower->username,
                'follower_avatar' => $follower->avatar_url
            ]
        ]);
    }

    public static function createMilestone($userId, $milestoneType, $milestoneData)
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

        return self::create([
            'user_id' => $userId,
            'type' => self::TYPE_MILESTONE,
            'title' => 'Milestone Achieved!',
            'message' => $messages[$milestoneType] ?? 'You\'ve achieved a new milestone!',
            'data' => array_merge($milestoneData, ['milestone_type' => $milestoneType])
        ]);
    }

    public static function createUpvote($userId, $senderId, $contentType, $contentId, $contentTitle)
    {
        $sender = User::find($senderId);
        return self::create([
            'user_id' => $userId,
            'sender_id' => $senderId,
            'type' => self::TYPE_UPVOTE,
            'title' => 'New Upvote',
            'message' => $sender->name . ' upvoted your ' . $contentType,
            'link' => $contentType === 'question' ? route('question.show', $contentId, false) : route('posts.show', $contentId, false),
            'data' => [
                'content_type' => $contentType,
                'content_id' => $contentId,
                'content_title' => $contentTitle,
                'sender_name' => $sender->name,
                'sender_username' => $sender->username
            ]
        ]);
    }

    public static function createComment($userId, $senderId, $contentType, $contentId, $contentTitle, $commentText)
    {
        $sender = User::find($senderId);
        return self::create([
            'user_id' => $userId,
            'sender_id' => $senderId,
            'type' => self::TYPE_COMMENT,
            'title' => 'New Comment',
            'message' => $sender->name . ' commented on your ' . $contentType,
            'link' => $contentType === 'question' ? route('question.show', $contentId, false) : route('posts.show', $contentId, false),
            'data' => [
                'content_type' => $contentType,
                'content_id' => $contentId,
                'content_title' => $contentTitle,
                'comment_text' => $commentText,
                'sender_name' => $sender->name,
                'sender_username' => $sender->username
            ]
        ]);
    }

    public static function createReply($userId, $senderId, $contentType, $contentId, $contentTitle, $replyText)
    {
        $sender = User::find($senderId);
        return self::create([
            'user_id' => $userId,
            'sender_id' => $senderId,
            'type' => self::TYPE_REPLY,
            'title' => 'New Reply',
            'message' => $sender->name . ' replied to your comment',
            'link' => $contentType === 'question' ? route('question.show', $contentId, false) : route('posts.show', $contentId, false),
            'data' => [
                'content_type' => $contentType,
                'content_id' => $contentId,
                'content_title' => $contentTitle,
                'reply_text' => $replyText,
                'sender_name' => $sender->name,
                'sender_username' => $sender->username
            ]
        ]);
    }

    public static function createAnswer($userId, $senderId, $questionId, $questionTitle, $answerContent)
    {
        $sender = User::find($senderId);
        return self::create([
            'user_id' => $userId,
            'sender_id' => $senderId,
            'type' => self::TYPE_ANSWER,
            'title' => 'New Answer',
            'message' => $sender->name . ' answered your question',
            'link' => route('question.show', $questionId, false),
            'data' => [
                'question_id' => $questionId,
                'question_title' => $questionTitle,
                'answer_content' => $answerContent,
                'sender_name' => $sender->name,
                'sender_username' => $sender->username
            ]
        ]);
    }

    public static function createReport($userId, $reportType, $reportId, $reportReason)
    {
        return self::create([
            'user_id' => $userId,
            'type' => self::TYPE_REPORT,
            'title' => 'Content Reported',
            'message' => 'Your ' . $reportType . ' has been reported for: ' . $reportReason,
            'data' => [
                'report_type' => $reportType,
                'report_id' => $reportId,
                'report_reason' => $reportReason
            ]
        ]);
    }

    public static function createReportAction($userId, $reportType, $reportId, $action, $adminMessage = null)
    {
        $actionMessages = [
            'resolved' => 'Your reported content has been reviewed and resolved.',
            'deleted' => 'Your reported content has been reviewed and removed.',
            'warned' => 'Your reported content has been reviewed and a warning has been issued.'
        ];

        return self::create([
            'user_id' => $userId,
            'type' => self::TYPE_REPORT_ACTION,
            'title' => 'Report Action Taken',
            'message' => $actionMessages[$action] ?? 'Action has been taken on your reported content.',
            'data' => [
                'report_type' => $reportType,
                'report_id' => $reportId,
                'action' => $action,
                'admin_message' => $adminMessage
            ]
        ]);
    }
}
