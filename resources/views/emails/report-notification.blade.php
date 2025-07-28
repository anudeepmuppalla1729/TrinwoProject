<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Report Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #3498db;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 20px;
            border: 1px solid #e9ecef;
        }
        .footer {
            background: #6c757d;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            font-size: 0.9em;
        }
        .alert {
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .alert-warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
                        <h1>Inqube - Report Notification</h1>
    </div>
    
    <div class="content">
        @if($action === 'deleted')
            <div class="alert alert-warning">
                <h3>Content Removal Notice</h3>
                <p>Your {{ $contentType }} has been removed from our platform due to a community report.</p>
            </div>
            
            <h4>Report Details:</h4>
            <ul>
                <li><strong>Content Type:</strong> {{ ucfirst($contentType) }}</li>
                <li><strong>Reason:</strong> {{ $report->reason }}</li>
                <li><strong>Reported by:</strong> {{ $report->reporter->name }}</li>
                <li><strong>Date:</strong> {{ $report->created_at->format('M d, Y') }}</li>
            </ul>
            
            <p>Please review our community guidelines to understand what content is acceptable on our platform.</p>
            
        @elseif($action === 'resolved')
            <div class="alert alert-success">
                <h3>Report Resolved</h3>
                <p>Your report has been reviewed and resolved by our moderation team.</p>
            </div>
            
            <h4>Report Details:</h4>
            <ul>
                <li><strong>Content Type:</strong> {{ ucfirst($contentType) }}</li>
                <li><strong>Your Reason:</strong> {{ $report->reason }}</li>
                <li><strong>Status:</strong> Resolved - No action taken</li>
                <li><strong>Date:</strong> {{ $report->created_at->format('M d, Y') }}</li>
            </ul>
            
            <p>Thank you for helping keep our community safe. Your report has been reviewed and no action was necessary.</p>
        @endif
        
        <p>If you have any questions about this decision, please contact our support team.</p>
        
        <a href="{{ url('/') }}" class="btn">Visit Our Forum</a>
    </div>
    
    <div class="footer">
                        <p>&copy; {{ date('Y') }} Inqube. All rights reserved.</p>
        <p>This is an automated message, please do not reply directly to this email.</p>
    </div>
</body>
</html> 