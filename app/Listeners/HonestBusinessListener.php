<?php

namespace App\Listeners;

use App\Events\HonestBusinessCheckingEvent;
use App\Packages\Services\HonestBusinessCheckingService;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Обработчик события проверки на учредительство
 *
 * @package App\Listeners
 */
class HonestBusinessListener implements ShouldQueue
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
     * @param HonestBusinessCheckingEvent $event
     * @return void
     */
    public function handle(HonestBusinessCheckingEvent $event)
    {
        (new HonestBusinessCheckingService($event->inn, $event->logger))->execute();
    }
}
