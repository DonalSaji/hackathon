<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NotificationHook;
use App\Models\NotificationTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InappNotificationController extends Controller
{
    // Fetch paginated notifications for the authenticated user
    public function notifications()
    {
        $perPage = 3;
        $user = Auth::user();

        $notifications = $user->notifications()->paginate($perPage);
        return response()->json($notifications);
    }

    // Mark all unread notifications as read
    public function readNotifications()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['status' => false, 'message' => 'User not authenticated.']);
        }

        $user->unreadNotifications->markAsRead();

        return response()->json(['status' => true, 'message' => 'All notifications marked as read.']);
    }

    // Clear all notifications for the authenticated user
    public function clearNotifications()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->back()->with('error', 'User not authenticated.');
        }

        $user->notifications()->delete();

        return redirect()->back()->with('success', 'All notifications cleared.');
    }

    //Templates
    public function notificationTemplates()
    {
        $templates = NotificationTemplate::all()->map(function ($template) {
            $template->hooks = is_string($template->hooks)
                ? json_decode($template->hooks, true)
                : $template->hooks;

            return $template;
        });
        return view('inapp_notification.all-templates', compact('templates'));
    }

    public function addNotificationTemplate()
    {
        $hooks = NotificationHook::all()->map(function ($hook) {
            return [
                'id' => $hook->id,
                'action' => $hook->action,
                'variables' => json_decode($hook->variables, true),
                'notification_template_id' => $hook->notification_template_id,
            ];
        });

        return view('inapp_notification.add-templates', compact('hooks'));
    }

    public function storeNotificationTemplate(Request $request)
    {
        $request->validate([
            'template_name' => 'required|string|max:255',
            'content' => 'required|string',
            'hooks' => 'required|array',
        ]);

        $hooks = $request->input('hooks');

        // Check if any selected hooks are already assigned
        $assignedHooks = NotificationHook::whereIn('id', $hooks)
            ->whereNotNull('notification_template_id')
            ->exists();

        if ($assignedHooks) {
            return back()->withErrors(['hooks' => 'One or more selected hooks are already assigned to another template.'])->withInput();
        }

        $hookNames = NotificationHook::whereIn('id', $hooks)->pluck('name')->toArray();

        $notificationTemplate = new NotificationTemplate();
        $notificationTemplate->name = $request->input('template_name');
        $notificationTemplate->template = $request->input('content');
        $notificationTemplate->hooks = json_encode($hookNames);
        $notificationTemplate->hooks_id = json_encode($hooks);
        $notificationTemplate->save();

        // Update the hooks to point to this template
        NotificationHook::whereIn('id', $hooks)->update([
            'notification_template_id' => $notificationTemplate->id
        ]);

        return redirect()->route('notification.templates')->with('success', 'Notification template created successfully.');
    }

    public function editNotificationTemplate($id)
    {
        $template = NotificationTemplate::findOrFail($id);
        $template->hooks = is_string($template->hooks)
            ? json_decode($template->hooks, true)
            : $template->hooks;

        $hooks = NotificationHook::all()->map(function ($hook) {
            return [
                'id' => $hook->id,
                'action' => $hook->action,
                'variables' => json_decode($hook->variables, true),
                'notification_template_id' => $hook->notification_template_id,
            ];
        });

        return view('inapp_notification.edit-templates', compact('template', 'hooks'));
    }

    public function updateNotificationTemplate(Request $request, $id)
    {
        $request->validate([
            'template_name' => 'required|string|max:255',
            'content' => 'required|string',
            'hooks' => 'required|array',
        ]);

        $hooks = $request->input('hooks');

        $hookNames = NotificationHook::whereIn('id', $hooks)->pluck('name')->toArray();

        $notificationTemplate = NotificationTemplate::findOrFail($id);
        $notificationTemplate->name = $request->input('template_name');
        $notificationTemplate->template = $request->input('content');
        $notificationTemplate->hooks = json_encode($hookNames);
        $notificationTemplate->hooks_id = json_encode($request->input('hooks'));
        $notificationTemplate->save();

        // Update the hooks to point to this template
        NotificationHook::whereIn('id', $hooks)->update([
            'notification_template_id' => $id
        ]);

        // Remove this template from hooks that are no longer selected
        NotificationHook::where('notification_template_id', $id)
            ->whereNotIn('id', $hooks)
            ->update(['notification_template_id' => null]);

        return redirect()->route('notification.templates')->with('success', 'Notification template updated successfully.');
    }



    //PWA Notification

    public function subscribe(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
        ]);

        $endpoint = $request->input('endpoint');
        $token = $request->input('keys.auth');
        $key = $request->input('keys.p256dh');

        $user = auth()->user();

        $user->updatePushSubscription($endpoint, $key, $token);

        return response()->json(['success' => true]);
    }
}
