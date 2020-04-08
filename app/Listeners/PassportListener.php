<?php

namespace App\Listeners;

use App\Events\PassportCheckingEvent;
use App\Packages\Services\PassportCheckingService;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Обработчик проверки паспорта физического лица
 *
 * @package App\Listeners
 */
class PassportListener implements ShouldQueue
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
     * @param PassportCheckingEvent $event
     * @return void
     */
    public function handle(PassportCheckingEvent $event)
    {

        (new PassportCheckingService($event->app, $event->logger))->execute();
    }
}
