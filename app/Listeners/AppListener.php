<?php

namespace App\Listeners;

use App\CheckingList;
use App\Events\AppEvent;
use App\Events\DisqCheckingEvent;
use App\Events\FedFsmCheckingEvent;
use App\Events\FindDepartmentCheckingEvent;
use App\Events\FsinChekingEvent;
use App\Events\FsspCheckingEvent;
use App\Events\FsspWantedCheckingEvent;
use App\Events\InterpolRedCheckingEvent;
use App\Events\InterpolYellowCheckingEvent;
use App\Events\MvdWantedCheckingEvent;
use App\Events\PassportCheckingEvent;
use App\Packages\Loggers\ApiLog;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Обработчик создания заявки
 *
 * Вызывает события проверки паспорта и проверки инн физического лица
 * @package App\Listeners
 */
class AppListener implements ShouldQueue
{

    protected $logger;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->logger = ApiLog::newInstance();
    }

    /**
     * Handle the event.
     *
     * @param AppEvent $event
     * @return void
     */
    public function handle(AppEvent $event)
    {

        $this->logger->setIdentity(identity($event->app->identity));

        //Переводим заявку из статус новая в статус проверяется.
        $event->app->status = 2;
        $event->app->save();

        $this->logger->info("AppListener: заявка", $event->app->toArray());

        //Генерируем событие проверки паспорта
        event(new PassportCheckingEvent($event->app, $this->logger->setIdentity(identity($event->app->identity, CheckingList::ITEM_PASSPORT))));

        //события проверки подразделения паспорта
        event(new FindDepartmentCheckingEvent($event->app, $this->logger->setIdentity(identity($event->app->identity, CheckingList::ITEM_FIND_CODE_DEPARTMENT))));

        //запускаем проверку фссп
        event(new FsspCheckingEvent($event->app, $this->logger->setIdentity(identity($event->app->identity, CheckingList::ITEM_FIND_FSSP))));

        //запускаем проверку фсин
        event(new FsinChekingEvent($event->app, $this->logger->setIdentity(identity($event->app->identity, CheckingList::ITEM_FIND_FSIN))));

        //запускаем поиск в списках террористов и экстремистов
        event(new FedFsmCheckingEvent($event->app, $this->logger->setIdentity(identity($event->app->identity, CheckingList::ITEM_FIND_TERRORIST))));

        //запускаем поиск интерпол желтые карточки
        event(new InterpolYellowCheckingEvent($event->app, $this->logger->setIdentity(identity($event->app->identity, CheckingList::ITEM_FIND_INTERPOL_YELLOW))));

        //запускаем поиск интерпол красные карточки
        event(new InterpolRedCheckingEvent($event->app, $this->logger->setIdentity(identity($event->app->identity, CheckingList::ITEM_FIND_INTERPOL_RED))));

        //поиск в федеральном розыске
        event(new MvdWantedCheckingEvent($event->app, $this->logger->setIdentity(identity($event->app->identity, CheckingList::ITEM_FIND_MVD_WANTED))));

        //поиск в местном розыске
        event(new FsspWantedCheckingEvent($event->app, $this->logger->setIdentity(identity($event->app->identity, CheckingList::ITEM_FIND_FSSP_WANTED))));

        //проверка по реестру дисквалифицированных лиц
        event(new DisqCheckingEvent($event->app, $this->logger->setIdentity(identity($event->app->identity, CheckingList::ITEM_FIND_DISQ))));

        //проверка в базе данных
        //TODO: Add db finder here
    }
}
