<?php

namespace App\Listeners;

use App\Events\FsinChekingEvent;
use App\Packages\Services\FsinCheckingService;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Обработчик поиска задолженностей фссп
 * @package App\Listeners
 */
class FsinListner implements ShouldQueue
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
    public function handle(FsinChekingEvent $event)
    {


        (new FsinCheckingService($event->app, $event->logger))->execute();

    }
}
