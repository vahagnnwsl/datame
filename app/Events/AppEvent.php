<?php

namespace App\Events;

use App\App;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Событие создание новой заявки на проверку физического лица
 *
 * @package App\Events
 */
class AppEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $app;

    /**
     * Create a new event instance.
     *
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
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
