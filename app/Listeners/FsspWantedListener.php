<?php

namespace App\Listeners;

use App\Events\FsspWantedCheckingEvent;
use App\Packages\Services\FsspWantedCheckingService;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Обработчик поиска в местном розыске
 *
 * @package App\Listeners
 */
class FsspWantedListener implements ShouldQueue
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
     * @param FsspWantedCheckingEvent $event
     * @return void
     */
    public function handle(FsspWantedCheckingEvent $event)
    {
        (new FsspWantedCheckingService($event->app, $event->logger))->execute();
    }
}
