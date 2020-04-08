<?php

namespace App\Listeners;

use App\CheckingList;
use App\Events\InnNotFoundEvent;
use App\Events\PassportNotValidEvent;
use App\Packages\Services\TraitCheckingList;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Обработчик не валидного паспорта
 * Устанавливает флаг поиски инн в успешный
 * @package App\Listeners
 */
class PassportNotValidListener implements ShouldQueue
{

    use TraitCheckingList;

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
     * @param PassportNotValidEvent $event
     * @return void
     */
    public function handle(PassportNotValidEvent $event)
    {
        $message = "Поиск ИНН не производился, так как паспорт не валидный";
        /** @var CheckingList $checkingItem */
        $checkingItem = $this->getCheckingList($event->app, CheckingList::ITEM_FIND_INN);
        $this->setMessage($checkingItem, $message);
        $event->logger->setIdentity(identity($event->app->identity, CheckingList::ITEM_FIND_INN));
        $event->logger->info($message, $event->app->toArray());

        $message = "Поиск подразделения не производился, так как паспорт не валидный";
        /** @var CheckingList $checkingItem */
        $checkingItem = $this->getCheckingList($event->app, CheckingList::ITEM_FIND_CODE_DEPARTMENT);
        $this->setMessage($checkingItem, $message);
        $event->logger->setIdentity(identity($event->app->identity, CheckingList::ITEM_FIND_CODE_DEPARTMENT));
        $event->logger->info($message, $event->app->toArray());

        event(new InnNotFoundEvent($event->app, $event->logger));
    }
}
