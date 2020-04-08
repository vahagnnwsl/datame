<?php

namespace App\Listeners;

use App\Events\InterpolYellowCheckingEvent;
use App\Packages\Services\InterpolYellowCheckingService;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Обработчик события поиска интерпол желтые карточки
 *
 * @package App\Listeners
 */
class InterpolYellowCheckingListener implements ShouldQueue
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
     * @param InterpolYellowCheckingEvent $event
     * @return void
     */
    public function handle(InterpolYellowCheckingEvent $event)
    {
        (new InterpolYellowCheckingService($event->app, $event->logger))->execute();
    }
}
