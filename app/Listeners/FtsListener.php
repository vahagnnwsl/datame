<?php

namespace App\Listeners;

use App\Events\FsinChekingEvent;
use App\Events\FtsCheckingEvent;
use App\Packages\Providers\FtServiceInformation;
use App\Packages\Services\FsinCheckingService;
use App\Packages\Services\FtsCheckingService;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Обработчик поиска задолженностей фссп
 * @package App\Listeners
 */
class FtsListener implements ShouldQueue
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
     * @param FtsCheckingEvent $event
     * @return void
     */
    public function handle(FtsCheckingEvent $event)
    {
        (new FtsCheckingService($event->inn, $event->logger))->execute();

    }
}
