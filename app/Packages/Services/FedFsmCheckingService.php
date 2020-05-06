<?php

namespace App\Packages\Services;


use App\App;
use App\CheckingList;
use App\FedFsm;
use App\Packages\Constants;
use App\Packages\Loggers\CustomLogger;
use App\Packages\Providers\FedFsmInformation;
use Throwable;

/**
 * Нахождение в списках террористов и экстремистов
 *
 * Class FedFsmCheckingService
 * @package App\Packages\Services
 */
class FedFsmCheckingService
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
        $checkingItem = $this->getCheckingList($this->app, CheckingList::ITEM_FIND_TERRORIST);

        if ($checkingItem->status === Constants::CHECKING_STATUS_SUCCESS) {
            return true;
        }

        $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_PROCESSING);

        try {

            $this->logger->info("Старт поиска списках террористов и экстремистов", $this->app->toArray());

            FedFsm::where('app_id', $this->app->id)->delete();
            $information = (new FedFsmInformation($this->app, $this->logger))->check();

            $item = new FedFsm();
            $item->app_id = $this->app->id;
            if($information->getStatusResult()) {
                //Если найдено
                if(is_array($information->getResult())) {
                    $item->status = $information->getResult()['status'];
                    $item->city_birth = $information->getResult()['city_birth'];
                    $item->full_name = $information->getResult()['full_name'];
                } else {
                    $item->status = $information->getResult();
                }

                $item->save();
                //отмечаем что проверка проведена
                $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_SUCCESS);
            } else {
                $item->error_message = $information->getResult();
                $item->save();
                $this->setError($checkingItem, $item->error_message);
            }

            $this->logger->info("Поиск в списках террористов и экстремистов завершен", $item->toArray());

            return true;

        } catch(Throwable $e) {
            $this->logger->error("error: {$e->getMessage()}, {$e->getTraceAsString()}");
            $this->setError($checkingItem, $e->getMessage());
        }

        return false;
    }
}
