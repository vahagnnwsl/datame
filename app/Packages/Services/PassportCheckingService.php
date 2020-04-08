<?php

namespace App\Packages\Services;


use App\App;
use App\CheckingList;
use App\Events\InnCheckingEvent;
use App\Events\PassportNotValidEvent;
use App\Packages\Constants;
use App\Packages\Loggers\CustomLogger;
use App\Packages\Providers\PassportInformation;
use App\Passport;
use Throwable;

/**
 * Сервис для проверки паспортных данных заявки
 *
 * Class PassportCheckingService
 * @package App\Packages\Services
 */
class PassportCheckingService
{
    use TraitCheckingList;

    /** @var App */
    protected $app;
    protected $logger;

    public function __construct(App $app, CustomLogger $logger)
    {
        $this->app = $app;
        $this->logger = $logger;
    }

    public function execute()
    {

        /** @var CheckingList $checkingItem */
        $checkingItem = $this->getCheckingList($this->app, CheckingList::ITEM_PASSPORT);
        $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_PROCESSING);

        try {

            $this->logger->info("Старт проверки паспорта", $this->app->toArray());

            //удаляем предыдующую проверку
            Passport::where('app_id', $this->app->id)->delete();

            $passportInformation = (new PassportInformation($this->app, $this->logger))->check();
            $passport = new Passport();
            $passport->app_id = $this->app->id;
            $passport->is_valid = $passportInformation->getStatusResult();
            if($passport->is_valid) {
                $passport->checking_state = Passport::STATE_SUCCESS;
                $passport->status = $passportInformation->getResult()['status'];
                $passport->age14 = $passportInformation->getResult()['age14'];
                $passport->age20 = $passportInformation->getResult()['age20'];
                $passport->age45 = $passportInformation->getResult()['age45'];
                $passport->passport_date_replace = $passportInformation->getResult()['passport_date_replace'];
                $passport->passport_serie_region = $passportInformation->getResult()['passport_serie_region'];
                $passport->passport_serie_year = $passportInformation->getResult()['passport_serie_year'];
                $passport->save();

                //пасспорт валидный, запускаем поиск инн
                //Генерируем событие проверки инн паспорта
                event(new InnCheckingEvent($this->app, $this->logger->setIdentity(identity($this->app->identity, CheckingList::ITEM_FIND_INN))));

            } else {
                $passport->status = $passportInformation->getResult()['status'];
                $passport->passport_serie_region = $passportInformation->getResult()['passport_serie_region'];
                $passport->passport_serie_year = $passportInformation->getResult()['passport_serie_year'];
                $passport->checking_state = Passport::STATE_ERROR;
                $passport->save();
                $this->logger->error("status", $passport->toArray());

                event(new PassportNotValidEvent($this->app, $this->logger));
            }
            //отмечаем что проверка проведена
            $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_SUCCESS);

            $this->logger->info("Проверка паспорта завершена", $passport->toArray());

            return true;

        } catch(Throwable $e) {
            $this->logger->error("error: {$e->getMessage()}, {$e->getTraceAsString()}");
            $this->setError($checkingItem, $e->getMessage());

        }

        return false;
    }
}