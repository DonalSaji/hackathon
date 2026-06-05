<?php

namespace App\Notifications;

use App\Models\NotificationHook;
use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;


class InAppNotification extends Notification
{


    public function __construct(public string $message, public string $title, public NotificationHook $hook, public string $link, public User $user)
    {
        //

    }
    public function broadcastNow()
    {
        return true;
    }

    public function via($notifiable)
    {
        return [WebPushChannel::class, 'database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        // You can customize the data stored in the database notification here
        return [
            'link' => $this->link,
            'icon' => $this->hook['icon'] ?? 'mdi mdi-account-lock-open',
            'bg_icon' => $this->hook['bg_icon'] ?? 'bg-success',
            'title' => $this->title ?? 'New Message',
            'body' => $this->message,
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        // return (new WebPushMessage)
        //     ->title('Approved!')
        //     ->icon('/approved-icon.png')
        //     ->body('Your account was approved!')
        //     ->action('View account', 'view_account')
        //     ->options(['TTL' => 1000]);
        // ->data(['id' => $notification->id])
        // ->badge()
        // ->dir()
        // ->image()
        // ->lang()
        // ->renotify()
        // ->requireInteraction()
        // ->tag()
        // ->vibrate()

        return (new WebPushMessage)
            ->title('Hackathon')        // Main title
            ->icon('/icon.png')                                     // Icon shown in the notification
            ->body($this->message)                                  // Body text
            ->data([
                'url' => $this->link,                               // Used when user clicks
                'notification' => [                                 // Service worker expects this
                    'title' => $this->title ?? 'New Notification',
                    'body' => $this->message,
                    'icon' => '/icon.png',
                    'data' => [
                        'url' => $this->link
                    ]
                ]
            ]);
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'link' => $this->link,
            'icon' => $this->hook['icon'] ?? 'mdi mdi-account-lock-open',
            'bg_icon' => $this->hook['bg_icon'] ?? 'bg-success',
            'title' => $this->title ?? 'New Message',
            'body' => $this->message,
        ]);
    }


    public function broadcastOn()
    {
        return new PrivateChannel('notifications.' . $this->user->id);
    }


    public function broadcastAs()
    {
        return 'InAppNotification';
    }
}
