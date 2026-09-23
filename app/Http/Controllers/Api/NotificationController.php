<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->latest()
            ->limit(100)
            ->get()
            ->map(fn ($n) => $this->payload($n));

        return response()->json(['data' => $notifications]);
    }

    public function markAsRead(Request $request, Notification $notification)
    {
        $this->authorize('view', $notification);
        $notification->update(['read_at' => now()]);
        return response()->json(['data' => $this->payload($notification)]);
    }

    public function markAllAsRead(Request $request)
    {
        Notification::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'All notifications marked as read.']);
    }

    protected function payload(Notification $notification): array
    {
        return [
            'id' => $notification->id,
            'user_id' => $notification->user_id,
            'type' => $notification->type,
            'data' => $notification->data,
            'read_at' => $notification->read_at,
            'created_at' => $notification->created_at,
        ];
    }
}
