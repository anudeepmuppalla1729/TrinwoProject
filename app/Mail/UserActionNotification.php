<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserActionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $action;
    public $details;

    public function __construct($user, $action, $details = [])
    {
        $this->user = $user;
        $this->action = $action;
        $this->details = $details;
    }

    public function build()
    {
        return $this->subject($this->getSubject())
            ->view('emails.user-action-notification');
    }

    protected function getSubject()
    {
        return match($this->action) {
            'answer_deleted' => 'Your answer has been deleted',
            'user_banned' => 'Your account has been banned',
            'user_deleted' => 'Your account has been deleted',
            'question_deleted' => 'Your question has been deleted',
            default => 'Account Notification'
        };
    }
} 