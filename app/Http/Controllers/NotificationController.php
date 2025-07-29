<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{
    public function markNotificationAsRead($notificationId)
    {
        $user = Auth::user();
        $notification = $user->unreadNotifications->where('id', $notificationId)->first();

        if ($notification) {
            $notification->markAsRead();
            return redirect()->back()->with('success', 'marquée comme lue.');
        }

        return redirect()->back()->with('error', 'Notification introuvable.');
    }
}
