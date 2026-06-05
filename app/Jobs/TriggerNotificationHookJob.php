<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\NotificationHookService;

class TriggerNotificationHookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $hook;
    public $data;
    /**
     * Create a new job instance.
     */

    public function __construct($hook, $data)
    {
        $this->hook = $hook;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationHookService $service): void
    {
        \Log::info("In TriggerNotificationHookJob handle method");
        $service->trigger($this->hook, $this->data);
    }
}
