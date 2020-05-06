<?php

namespace App\Packages\Services;


use App\CheckingList;
use App\Debtor;
use App\FindInn;
use App\Packages\Constants;
use App\Packages\Loggers\CustomLogger;
use App\Packages\Providers\DebtorInformation;
use Throwable;

/**
 * Проверка банкроства
 *
 * Class DisqCheckingService
 * @package App\Packages\Services
 */
class DebtorCheckingService
{
    use TraitCheckingList;

    /** @var FindInn */
    protected $inn;
    protected $logger;

    public function __construct(FindInn $inn, CustomLogger $logger)
    {
        $this->inn = $inn;
        $this->logger = $logger;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        /** @var CheckingList $checkingItem */
        $checkingItem = $this->getCheckingList($this->inn->app, CheckingList::ITEM_FIND_DEBTOR);

        if ($checkingItem->status === Constants::CHECKING_STATUS_SUCCESS) {
            return true;
        }

        $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_PROCESSING);


        try {

            $this->logger->info("Старт проверки банкротства", $this->inn->toArray());

            Debtor::where('find_inn_id', $this->inn->id)->delete();

            $information = (new DebtorInformation($this->inn, $this->logger))->check();
            if ($information->getStatusResult()) {
                if (is_array($information->getResult())) {
                    //проверка успешно проведена, сохраняем начисления
                    foreach ($information->getResult() as $item) {
                        $findItem = new Debtor();
                        $findItem->find_inn_id = $this->inn->id;
                        $findItem->result = "Является банкротом";
                        $findItem->category = trim($item['category']);
                        $findItem->inn = trim($item['INN']);
                        $findItem->snils = trim($item['SNILS']);
                        $findItem->ogrnip = trim($item['OGRNIP']);
                        $findItem->region = trim($item['region']);
                        $findItem->live_address = trim($item['card_data']['место_жительства']);
                        $findItem->save();
                    }
                } else {
                    $findItem = new Debtor();
                    $findItem->find_inn_id = $this->inn->id;
                    $findItem->result = $information->getResult();
                    $findItem->save();
                }
                //отмечаем что проверка проведена
                $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_SUCCESS);
            } else {
                $this->logger->error("Ошибка проверки банкротства");
                $this->setError($checkingItem, $information->getResult());
            }

            if (is_array($information->getResult()))
                $this->logger->info("Завершен поиск проверки банкротства", $information->getResult());
            else
                $this->logger->info("Завершен поиск проверки банкротства: {$information->getResult()}");

            return true;

        } catch (Throwable $e) {
            $this->logger->error("error: {$e->getMessage()}, {$e->getTraceAsString()}");
            $this->setError($checkingItem, $e->getMessage());
        }

        return false;
    }
}
