<?php

namespace App\Listeners;

use App\Events\FsspCheckingEvent;
use App\Packages\Services\FsspCheckingService;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Обработчик поиска задолженностей фссп
 * @package App\Listeners
 */
class FsspListener implements ShouldQueue
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
     * @param FsspCheckingEvent $event
     * @return void
     */
    public function handle(FsspCheckingEvent $event)
    {

        (new FsspCheckingService($event->app, $event->logger))->execute();

    }
}
