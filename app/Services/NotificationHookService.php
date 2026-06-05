<?php

namespace App\Services;

use App\Models\Hook;
use App\Models\User;
use App\Models\NotificationHook;
use App\Notifications\InAppNotification;
use App\Models\NotificationSetting;
use App\Models\NotificationTemplate;
use Illuminate\Support\Facades\Notification;
use Log;

class NotificationHookService
{
    public function trigger(string $hookName, array $data)
    {
        $hook = NotificationHook::where('name', $hookName)->firstOrFail();
        $permissionGroup = $hook->permission_group;

        // Log::info("Triggering hook: $hookName with data: " . json_encode($data));
        // Log::info("hook: " . json_encode($hook));
        // Log::info("Permission Group: $permissionGroup");


        $users = User::get();


        //Log::info("Users with permission group $permissionGroup: " . json_encode($users));

        foreach ($users as $user) {
            //Log::info("Processing user ID: " . $user->id);
            // Log::info("User found: " . json_encode($user));
            if (!$user) continue;

            // TODO: Check role-based access if needed
            if (!$user->hasPermission($hook->permission_name)) continue;

            // Get users who disabled this permission group
            if (NotificationSetting::where('user_id', $user->id)
                ->whereJsonContains('notification_groups', $permissionGroup)
                ->exists()
            ) {
                // Log::info("User ID " . $user->id . " has not disabled permission group: " . $permissionGroup);
                continue;
            }

            // Get template(s)
            // Log::info("Sending notification to user ID: " . $user->id);
            $template = NotificationTemplate::whereJsonContains('hooks', $hookName)->first();
            // Log::info("Found template: " . json_encode($template));

            if ($template) {
                $message = $this->renderTemplate($template->template, $data);
                // Log::info("Rendered message: " . $message);
                Notification::send($user, new InAppNotification($message, $template->name, $hook, $data['link'], $user));
                Log::info("Notification sent to user ID: " . $user->id);
            }
        }
    }

    private function renderTemplate($template, $data)
    {

        foreach ($data as $key => $value) {
            $template = str_replace('{{' . $key . '}}', $value, $template);
        }
        return $template;
    }
}
