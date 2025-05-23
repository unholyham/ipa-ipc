<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Fetch unread notifications for the authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUnreadNotifications(Request $request){
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        $unreadNotifications = Auth::user()->unreadNotifications;

        return response()->json($unreadNotifications);
    }

    /**
     * Mark all unread notifications for the authenticated user as read.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

     public function markAllAsRead(Request $request){
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['message' => 'All notifications marked as read.']);
     }

     public function markSingleAsRead(DatabaseNotification $notification)
    {
        // SECURITY CHECK: Ensure the notification belongs to the authenticated user.
        // This prevents users from marking other users' notifications as read.
        if (Auth::id() !== $notification->notifiable_id) {
            return response()->json(['message' => 'Unauthorized to mark this notification as read.'], 403);
        }

        $notification->markAsRead();

        return response()->json(['message' => 'Notification marked as read successfully.']);
    }
}
