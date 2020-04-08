<?php

namespace App\Listeners;

use App\Events\InterpolRedCheckingEvent;
use App\Packages\Services\InterpolRedCheckingService;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Обработчик события поиска интерпол желтые карточки
 *
 * @package App\Listeners
 */
class InterpolRedCheckingListener implements ShouldQueue
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
     * @param InterpolRedCheckingEvent $event
     * @return void
     */
    public function handle(InterpolRedCheckingEvent $event)
    {
        (new InterpolRedCheckingService($event->app, $event->logger))->execute();
    }
}
