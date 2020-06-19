<?php

namespace App\Listeners;

use App\CheckingList;
use App\Events\InnNotFoundEvent;
use App\Packages\Services\TraitCheckingList;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Обработчик не найденного инн
 * Устанавливает флаг проверки по налогам в успешный и ошибку поиска инн
 *
 * @package App\Listeners
 */
class InnNotFoundListener implements ShouldQueue
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
     * @param InnNotFoundEvent $event
     * @return void
     */
    public function handle(InnNotFoundEvent $event)
    {
        $event->logger->info("заявка", $event->app->toArray());

        $message = "Поиск по налоговым сборам провести не представляется возможным - ИНН не найден!";
        /** @var CheckingList $checkingItem */
        $checkingItem = $this->getCheckingList($event->app, CheckingList::ITEM_FIND_TAX);
        $this->setMessage($checkingItem, $message);
        $event->logger->setIdentity(identity($event->app->identity, CheckingList::ITEM_FIND_TAX));
        $event->logger->info($message);

        $message = "Поиск по наличию банкротства провести не представляется возможным - ИНН не найден!";
        $checkingItem = $this->getCheckingList($event->app, CheckingList::ITEM_FIND_DEBTOR);
        $this->setMessage($checkingItem, $message);
        $event->logger->setIdentity(identity($event->app->identity, CheckingList::ITEM_FIND_DEBTOR));
        $event->logger->info($message);

        $message = "Поиск по установлению учредительства и руководства провести не представляется возможным - ИНН не найден!";
        $checkingItem = $this->getCheckingList($event->app, CheckingList::ITEM_FIND_HONEST_BUSINESS);
        $this->setMessage($checkingItem, $message);
        $event->logger->setIdentity(identity($event->app->identity, CheckingList::ITEM_FIND_HONEST_BUSINESS));
        $event->logger->info($message);
    }
}
