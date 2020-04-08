<?php

namespace App\Packages\Services;


use App\CheckingList;
use App\FindInn;
use App\FindTax;
use App\Packages\Constants;
use App\Packages\Loggers\CustomLogger;
use App\Packages\Providers\TaxInformation;
use Throwable;

/**
 * Сервис для проверки налоговых задолжностей по инн
 *
 * Class TaxCheckingService
 * @package App\Packages\Services
 */
class TaxCheckingService
{
    use TraitCheckingList;

    /** @var FindInn */
    protected $findInn;
    protected $logger;

    public function __construct(FindInn $findInn, CustomLogger $logger)
    {
        $this->findInn = $findInn;
        $this->logger = $logger;
    }

    public function execute()
    {
        /** @var CheckingList $checkingItem */
        $checkingItem = $this->getCheckingList($this->findInn->app, CheckingList::ITEM_FIND_TAX);
        $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_PROCESSING);

        try {

            $this->logger->info("Старт поиска налоговых задолженностей", $this->findInn->toArray());

            //инн найден без ошибок
            if(!is_null($this->findInn->inn)) {
                $items = (new TaxInformation($this->findInn->inn, $this->logger))->check();

                //если запрос без ошибок
                if($items->getStatusResult()) {
                    //если найдены начисления
                    if(is_array($items->getResult())) {
                        FindTax::where('find_inn_id', $this->findInn->id)->delete();
                        foreach($items->getResult() as $item) {
                            $this->findInn->tax()->save(
                                new FindTax([
                                    'article' => $item['article'],
                                    'number' => $item['number'],
                                    'date_protocol' => $item['date_protocol'],
                                    'amount' => $item['amount'],
                                    'name' => $item['name'],
                                    'inn' => $item['inn'],
                                    'kpp' => $item['kpp'],
                                    'okato' => $item['okato'],
                                    'bik' => $item['bik'],
                                    'rs' => $item['rs'],
                                    'kbk' => $item['kbk']
                                ])
                            );
                        }
                    }
                    //отмечаем что проверка проведена
                    $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_SUCCESS);
                } else {
                    //запрос отработал с ошибками
                    $this->logger->error("Ошибка поиска налогов: {$items->getResult()}");
                    $this->setError($checkingItem, "Ошибка поиска налогов: {$items->getResult()}");
                }
            } else {
                $this->logger->error("Поле инн пустое, не проводим проверку.");
                $this->setError($checkingItem, "Поле инн пустое, не проводим проверку.");
            }

            $this->logger->info("Проверка поиска налоговых задолженностей завершена");

            return true;

        } catch(Throwable $e) {
            $this->logger->error("error: {$e->getMessage()}, {$e->getTraceAsString()}");
            $this->setError($checkingItem, $e->getMessage());
        }

        return false;

    }
}