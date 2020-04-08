<?php

namespace App\Listeners;

use App\Events\MvdWantedCheckingEvent;
use App\Packages\Services\MvdWantedCheckingService;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Обработчик поиска в федеральном розыске
 *
 * @package App\Listeners
 */
class MvdWantedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param MvdWantedCheckingEvent $event
     * @return void
     */
    public function handle(MvdWantedCheckingEvent $event)
    {
        (new MvdWantedCheckingService($event->app, $event->logger))->execute();
    }
}
