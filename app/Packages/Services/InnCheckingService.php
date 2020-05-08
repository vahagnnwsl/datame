<?php

namespace App\Packages\Services;


use App\App;
use App\CheckingList;
use App\Events\DebtorCheckingEvent;
use App\Events\HonestBusinessCheckingEvent;
use App\Events\InnFoundEvent;
use App\Events\InnNotFoundEvent;
use App\FindInn;
use App\Packages\Constants;
use App\Packages\Loggers\CustomLogger;
use App\Packages\Providers\InnInformation;
use App\Packages\Providers\InnPdfInformation;
use Throwable;

/**
 * Сервис для проверки инн физического лица по заявке
 *
 * Class InnCheckingService
 * @package App\Packages\Services
 */
class InnCheckingService
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

    /**
     * @return bool
     */
    public function execute()
    {
        /** @var CheckingList $checkingItem */
        $checkingItem = $this->getCheckingList($this->app, CheckingList::ITEM_FIND_INN);

        if ($checkingItem->status === Constants::CHECKING_STATUS_SUCCESS) {
            return true;
        }

        $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_PROCESSING);

        try {

            $this->logger->info("Старт поиска инн", $this->app->toArray());


            FindInn::where('app_id', $this->app->id)->delete();
            $innInformation = (new InnInformation($this->app, $this->logger))->check();
            $findInn = new FindInn();
            $findInn->app_id = $this->app->id;
            $findInn->type_inn = FindInn::INDIVIDUAL_INN;
            if($innInformation->getStatusResult()) {
                $findInn->inn = $innInformation->getResult();

                try {
                    (new InnPdfInformation($findInn->inn))->check();
                }catch (\Exception $exception){
                    $this->logger->error("error22226: ".$exception->getMessage());
                }

            } else {
                $findInn->error_message = $innInformation->getResult();
                $this->setMessage($checkingItem, $findInn->error_message);
            }

            if($findInn->save()) {
                //проверка паспорта проведена
//            $checkingItem->is_checked = true;
//            $checkingItem->save();
                if(!is_null($findInn->inn) && is_numeric($findInn->inn)) {
                    event(new InnFoundEvent($findInn, $this->logger->setIdentity(identity($this->app->identity, CheckingList::ITEM_FIND_INN))));
                    event(new DebtorCheckingEvent($findInn, $this->logger->setIdentity(identity($this->app->identity, CheckingList::ITEM_FIND_DEBTOR))));
                    event(new HonestBusinessCheckingEvent($findInn, $this->logger->setIdentity(identity($this->app->identity, CheckingList::ITEM_FIND_HONEST_BUSINESS))));
                } else {
                    event(new InnNotFoundEvent($this->app, $this->logger->setIdentity(identity($this->app->identity, CheckingList::ITEM_FIND_INN))));
                }
            };

            //отмечаем что проверка проведена
            $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_SUCCESS);

            $this->logger->info("Поиск инн завершен", $findInn->toArray());

            return true;

        } catch(Throwable $e) {
            $this->logger->error("error: {$e->getMessage()}, {$e->getTraceAsString()}");
            $this->setError($checkingItem, $e->getMessage());
        }

        return false;

    }
}
