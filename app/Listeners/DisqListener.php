<?php

namespace App\Listeners;

use App\Events\DisqCheckingEvent;
use App\Packages\Services\DisqCheckingService;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Обработчик события проверки по реестру дисквалифицированных лиц
 *
 * @package App\Listeners
 */
class DisqListener implements ShouldQueue
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
     * @param DisqCheckingEvent $event
     * @return void
     */
    public function handle(DisqCheckingEvent $event)
    {
        (new DisqCheckingService($event->app, $event->logger))->execute();
    }
}
