<?php

namespace App\Listeners;

use App\Events\FedFsmCheckingEvent;
use App\Packages\Services\FedFsmCheckingService;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Обработчик события поиска в списках террористов и экстремистов
 *
 * @package App\Listeners
 */
class FedFsmListener implements ShouldQueue
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
     * @param FedFsmCheckingEvent $event
     * @return void
     */
    public function handle(FedFsmCheckingEvent $event)
    {
        (new FedFsmCheckingService($event->app, $event->logger))->execute();
    }
}
