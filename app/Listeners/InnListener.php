<?php

namespace App\Listeners;

use App\Events\InnCheckingEvent;
use App\Packages\Services\InnCheckingService;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Обработчик  поиска инн по паспортным данным
 * @package App\Listeners
 */
class InnListener implements ShouldQueue
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
     * @param InnCheckingEvent $event
     * @return void
     */
    public function handle(InnCheckingEvent $event)
    {

        (new InnCheckingService($event->app, $event->logger))->execute();
    }
}
