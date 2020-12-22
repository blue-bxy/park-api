<?php

namespace App\Jobs;

use App\Models\Users\Message;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class JPushSendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $message;

    /**
     * Create a new job instance.
     *
     * @param Message $message
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;

        $this->delay($message->send_time);
    }

    /**
     * Execute the job.
     *
     * @param NotificationService $service
     *
     * @return void
     */
    public function handle(NotificationService $service)
    {
        $service->sendNow($this->message);
    }
}
