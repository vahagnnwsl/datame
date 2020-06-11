<?php


namespace App\Listeners;


use App\Events\CustomDataCheckingEvent;
use App\Packages\Services\CustomDataCheckingService;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Обработчик события проверки в базе данных
 *
 * @package App\Listeners
 */
class FindCustomDataListener implements ShouldQueue
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
     * @param CustomDataCheckingEvent $event
     * @return void
     */
    public function handle(CustomDataCheckingEvent $event)
    {
        (new CustomDataCheckingService($event->app, $event->logger))->execute();
    }
}