<?php

namespace App\Listeners;

use App\Events\InnFoundEvent;
use App\Packages\Services\TaxCheckingService;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Обработчик найденого инн
 * Вызывает событие проверки задолженностей по налогам
 * @package App\Listeners
 */
class InnFoundListener implements ShouldQueue
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
     * @param InnFoundEvent $event
     * @return void
     */
    public function handle(InnFoundEvent $event)
    {
        (new TaxCheckingService($event->inn, $event->logger))->execute();
    }
}
