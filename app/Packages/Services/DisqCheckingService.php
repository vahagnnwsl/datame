<?php

namespace App\Packages\Services;


use App\App;
use App\CheckingList;
use App\Disq;
use App\Packages\Constants;
use App\Packages\Loggers\CustomLogger;
use App\Packages\Providers\DisqInformation;
use Throwable;

/**
 * Проверка по реестру дисквалифицированных лиц
 *
 * Class DisqCheckingService
 * @package App\Packages\Services
 */
class DisqCheckingService
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
        $checkingItem = $this->getCheckingList($this->app, CheckingList::ITEM_FIND_DISQ);
        $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_PROCESSING);

        try {

            $this->logger->info("Старт проверки по реестру дисквалифицированных лиц", $this->app->toArray());

            Disq::where('app_id', $this->app->id)->delete();

            $information = (new DisqInformation($this->app, $this->logger))->check();
            if($information->getStatusResult()) {
                if(is_array($information->getResult())) {
                    //проверка успешно проведена, сохраняем начисления
                    foreach($information->getResult() as $item) {
                        $findItem = new Disq();
                        $findItem->app_id = $this->app->id;
                        $findItem->result = "Является дисквалифицированным лицом";
                        $findItem->data_con_discv = dt_parse($item['ДатаКонДискв']);
                        $findItem->naim_org_prot = $item['НаимОргПрот'];
                        $findItem->dolgnost = $item['Должность'];
                        $findItem->naim_org = $item['НаимОрг'];
                        $findItem->data_nach_discv = dt_parse($item['ДатаНачДискв']);
                        $findItem->dolgnost_sud = $item['ДолжностьСуд'];
                        $findItem->mesto_rogd = $item['МестоРожд'];
                        $findItem->discv_srok = $item['ДисквСрок'];
                        $findItem->kvalivikaciya_tekst = $item['КвалификацияТекст'];
                        $findItem->nom_zap = $item['НомЗап'];
                        $findItem->fio_sud = $item['ФИОСуд'];
                        $findItem->save();
                    }
                } else {
                    $findItem = new Disq();
                    $findItem->app_id = $this->app->id;
                    $findItem->result = $information->getResult();
                    $findItem->save();
                }
                //отмечаем что проверка проведена
                $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_SUCCESS);
            } else {
                $this->logger->error("Ошибка проверки по реестру дисквалифицированных лиц");
                $this->setError($checkingItem, "Ошибка проверки по реестру дисквалифицированных лиц:" . $information->getResult());
            }

            if(is_array($information->getResult()))
                $this->logger->info("Завершен поиск проверки по реестру дисквалифицированных лиц", $information->getResult());
            else
                $this->logger->info("Завершен поиск проверки по реестру дисквалифицированных лиц: {$information->getResult()}");

            return true;

        } catch(Throwable $e) {
            $this->logger->error("error: {$e->getMessage()}, {$e->getTraceAsString()}");
            $this->setError($checkingItem, $e->getMessage());
        }

        return false;
    }
}