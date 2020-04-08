<?php

namespace App\Packages\Services;


use App\App;
use App\CheckingList;
use App\FindFssp;
use App\Packages\Constants;
use App\Packages\Loggers\CustomLogger;
use App\Packages\Providers\FsspInformation;
use Throwable;

/**
 * Сервис для проверки фссп
 *
 * Class FssCheckingService
 * @package App\Packages\Services
 */
class FsspCheckingService
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
        $checkingItem = $this->getCheckingList($this->app, CheckingList::ITEM_FIND_FSSP);
        $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_PROCESSING);

        try {

            $this->logger->info("Старт поиска фссп", $this->app->toArray());

            FindFssp::where('app_id', $this->app->id)->delete();

            $information = (new FsspInformation($this->app, $this->logger))->check();
            if($information->getStatusResult()) {
                //проверка успешно проведена, сохраняем начисления
                foreach($information->getResult() as $item) {
                    $findItem = new FindFssp();
                    $findItem->app_id = $this->app->id;
                    $findItem->fio = $item['fio'];
                    $findItem->number = $item['number'];
                    $findItem->amount = $item['amount'];
                    $findItem->nazn = $item['nazn'];
                    $findItem->name_poluch = $item['name_poluch'];
                    $findItem->bik = $item['bik'];
                    $findItem->rs = $item['rs'];
                    $findItem->bank = $item['bank'];
                    $findItem->kpp = $item['kpp'];
                    $findItem->inn = $item['inn'];
                    $findItem->date_protocol = dt_parse($item['date_protocol']);
                    $findItem->contact = $item['contact'];
                    $findItem->save();
                }

                //отмечаем что проверка проведена успешно
                $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_SUCCESS);

            } else {
                if(is_array($information->getResult())) {
                    $checkingItem->message = "Не удалось получить ответ от фссп по техническим причинам";
                    $this->setError($checkingItem, $checkingItem->message);
                } else {
                    $checkingItem->message = $information->getResult();
                    $this->setError($checkingItem, $checkingItem->message);
                }
                $this->logger->error("Ошибка поиска фссп");
            }

            $this->logger->info("Поиск фссп завершен", $information->getResult());

            return true;

        } catch(Throwable $e) {
            $this->logger->error("error: {$e->getMessage()}, {$e->getTraceAsString()}");
            $this->setError($checkingItem, $e->getMessage());

        }

        return false;
    }
}