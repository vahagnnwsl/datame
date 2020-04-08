<?php

namespace App\Listeners;

use App\Events\DebtorCheckingEvent;
use App\Packages\Services\DebtorCheckingService;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Обработчик события проверки банкротства
 *
 * @package App\Listeners
 */
class DebtorListener implements ShouldQueue
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
     * @param DebtorCheckingEvent $event
     * @return void
     */
    public function handle(DebtorCheckingEvent $event)
    {
        (new DebtorCheckingService($event->inn, $event->logger))->execute();
    }
}
