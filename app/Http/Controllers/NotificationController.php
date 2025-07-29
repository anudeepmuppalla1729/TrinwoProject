<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.notifications.index', compact('notifications'));
    }



    public function createTestNotification()
    {
        $user = Auth::user();
        
        // Create a test notification
        $notification = Notification::create([
            'user_id' => $user->user_id,
            'type' => 'welcome',
            'title' => 'Test Notification',
            'message' => 'This is a test notification to verify the system is working.',
            'is_read' => false
        ]);

        return response()->json(['success' => true, 'message' => 'Test notification created']);
    }

    public function getUnreadCount()
    {
        $user = Auth::user();
        $count = $user->notifications()->unread()->count();
        
        return response()->json(['count' => $count]);
    }

    public function getNotifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'link' => $notification->link,
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'icon' => $notification->getIcon(),
                    'color' => $notification->getColor(),
                    'sender' => $notification->sender ? [
                        'name' => $notification->sender->name,
                        'username' => $notification->sender->username,
                        'avatar' => $notification->sender->avatar_url
                    ] : null,
                    'data' => $notification->data
                ];
            });

        return response()->json(['notifications' => $notifications]);
    }

    public function markAsRead(Request $request)
    {
        $user = Auth::user();
        $notificationId = $request->input('notification_id');

        if ($notificationId) {
            // Mark specific notification as read
            $notification = $user->notifications()->find($notificationId);
            if ($notification) {
                $notification->markAsRead();
                return response()->json(['success' => true]);
            }
        } else {
            // Mark all notifications as read
            $user->notifications()->unread()->update([
                'is_read' => true,
                'read_at' => now()
            ]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    public function markAsUnread(Request $request)
    {
        $user = Auth::user();
        $notificationId = $request->input('notification_id');

        $notification = $user->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsUnread();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    public function delete(Request $request)
    {
        $user = Auth::user();
        $notificationId = $request->input('notification_id');

        if ($notificationId) {
            // Delete specific notification
            $notification = $user->notifications()->find($notificationId);
            if ($notification) {
                $notification->delete();
                return response()->json(['success' => true]);
            }
        } else {
            // Delete all read notifications
            $user->notifications()->read()->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    public function clearAll()
    {
        $user = Auth::user();
        $user->notifications()->delete();
        
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->notifications()->unread()->update([
            'is_read' => true,
            'read_at' => now()
        ]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Send a notification to all users
     */
    public function sendToAll(Request $request)
    {
        // Optionally, add admin check here
        // if (!Auth::user() || !Auth::user()->isAdmin()) { abort(403); }

        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'nullable|string|max:50',
            'link' => 'nullable|string|max:255',
        ]);

        $type = $request->input('type', 'general');
        $link = $request->input('link');
        $title = $request->input('title');
        $message = $request->input('message');

        $users = \App\Models\User::all(['user_id']);
        $count = 0;
        foreach ($users as $user) {
            \App\Models\Notification::create([
                'user_id' => $user->user_id,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'link' => $link,
                'is_read' => false
            ]);
            $count++;
        }
        return response()->json(['success' => true, 'message' => "Notification sent to $count users."]);
    }
}
