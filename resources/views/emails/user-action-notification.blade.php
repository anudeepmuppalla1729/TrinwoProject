<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Account Notification</title>
</head>
<body style="background: #f4f6fb; font-family: 'Segoe UI', Arial, sans-serif; margin: 0; padding: 0;">
    <div style="max-width: 520px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(67,97,238,0.08); overflow: hidden;">
        <div style="background: #4361ee; color: #fff; padding: 24px 32px;">
            <h2 style="margin: 0; font-size: 1.5rem;">
                @if($action === 'answer_deleted')
                    Answer Deleted
                @elseif($action === 'user_banned')
                    Account Banned
                @elseif($action === 'user_deleted')
                    Account Deleted
                @elseif($action === 'question_deleted')
                    Question Deleted
                @elseif($action === 'post_deleted')
                    Post Deleted
                @else
                    Account Notification
                @endif
            </h2>
        </div>
        <div style="padding: 32px;">
            <p style="font-size: 1.1rem; color: #222; margin-top: 0;">Hello <b>{{ $user->name ?? $user->username }}</b>,</p>

            @if($action === 'answer_deleted')
                <div style="background: #ffe5e5; border-left: 4px solid #e63946; padding: 16px; border-radius: 6px; margin-bottom: 18px;">
                    <b>Your answer to the question:</b><br>
                    <span style="color: #222;">"{{ $details['question_title'] ?? '' }}"</span>
                </div>
                <p style="color: #e63946;">has been <b>deleted</b> by an administrator for violating our guidelines or due to a report.</p>
            @endif

            @if($action === 'user_banned')
                <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 16px; border-radius: 6px; margin-bottom: 18px;">
                    <b>Your account has been <span style="color: #e67e22;">banned</span> by an administrator.</b>
                </div>
                <p style="color: #e67e22;">If you believe this is a mistake, please contact support for more information.</p>
            @endif

            @if($action === 'user_deleted')
                <div style="background: #ffe5e5; border-left: 4px solid #e63946; padding: 16px; border-radius: 6px; margin-bottom: 18px;">
                    <b>Your account has been <span style="color: #e63946;">deleted</span> by an administrator.</b>
                </div>
                <p style="color: #e63946;">If you believe this is a mistake, please contact support.</p>
            @endif

            @if($action === 'question_deleted')
                <div style="background: #e0f7fa; border-left: 4px solid #00bcd4; padding: 16px; border-radius: 6px; margin-bottom: 18px;">
                    <b>Your question:</b><br>
                    <span style="color: #222;">"{{ $details['question_title'] ?? '' }}"</span>
                </div>
                <p style="color: #00bcd4;">has been <b>deleted</b> by an administrator for violating our guidelines or due to a report.</p>
            @endif

            @if($action === 'post_deleted')
                <div style="background: #f1faee; border-left: 4px solid #457b9d; padding: 16px; border-radius: 6px; margin-bottom: 18px;">
                    <b>Your post:</b><br>
                    <span style="color: #222;">"{{ $details['post_title'] ?? '' }}"</span>
                </div>
                <p style="color: #457b9d;">has been <b>deleted</b> by an administrator for violating our guidelines or due to a report.</p>
            @endif

            <p style="margin-top: 32px; color: #555; font-size: 1rem;">If you have any questions, please reply to this email or contact our support team.</p>
            <div style="margin-top: 32px; text-align: center;">
                <span style="color: #4361ee; font-weight: bold; font-size: 1.1rem;">Inqube Team</span>
            </div>
        </div>
    </div>
    <div style="text-align: center; color: #aaa; font-size: 0.9rem; margin-top: 18px;">
                        &copy; {{ date('Y') }} Inqube. All rights reserved.
    </div>
</body>
</html> 