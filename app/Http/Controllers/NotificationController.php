<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display all notifications for the authenticated user.
     */
    public function index(Request $request): View
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(15);

        return view('pages.notifications', [
            'notifications' => $notifications,
        ]);
    }

    /**
     * Return recent notifications and unread count.
     */
    public function recent(Request $request): JsonResponse
    {
        $user = $request->user();

        $notifications = $user->notifications()
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->data['title']
                        ?? 'Notification',
                    'message' => $notification->data['message']
                        ?? '',
                    'url' => $notification->data['url']
                        ?? null,
                    'type' => $notification->data['type']
                        ?? 'info',
                    'read' => $notification->read_at !== null,
                    'created_at' => $notification->created_at
                        ->diffForHumans(),
                ];
            });

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }

    /**
     * Mark one notification as read.
     */
    public function markAsRead(
        Request $request,
        string $notification
    ): RedirectResponse|JsonResponse {
        $item = $request->user()
            ->notifications()
            ->findOrFail($notification);

        if ($item->read_at === null) {
            $item->markAsRead();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read.',
            ]);
        }

        return back()->with(
            'success',
            'Notification marked as read.'
        );
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(
        Request $request
    ): RedirectResponse|JsonResponse {
        $request->user()
            ->unreadNotifications()
            ->update([
                'read_at' => now(),
            ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read.',
            ]);
        }

        return back()->with(
            'success',
            'All notifications marked as read.'
        );
    }
}
