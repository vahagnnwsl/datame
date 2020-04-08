<?php

namespace App\Events;

use App\App;
use App\Packages\Loggers\CustomLogger;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * События не валидного паспора
 *
 * @package App\Events
 */
class PassportNotValidEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $app;
    public $logger;

    /**
     * Create a new event instance.
     *
     * @param App $app
     * @param CustomLogger $logger
     */
    public function __construct(App $app, CustomLogger $logger)
    {
        $this->app = $app;
        $this->logger = $logger;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
