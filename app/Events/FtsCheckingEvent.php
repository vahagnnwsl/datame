<?php

namespace App\Events;

use App\FindInn;
use App\Packages\Loggers\CustomLogger;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Запуск для проверки фссп
 *
 * @package App\Events
 */
class FtsCheckingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $inn;
    public $logger;

    /**
     * Create a new event instance.
     *
     * @param FindInn $inn
     * @param CustomLogger $logger
     */
    public function __construct(FindInn $inn, CustomLogger $logger)
    {
        $this->inn = $inn;
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
