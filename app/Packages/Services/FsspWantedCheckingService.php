<?php

namespace App\Packages\Services;


use App\App;
use App\CheckingList;
use App\FsspWanted;
use App\Packages\Constants;
use App\Packages\Loggers\CustomLogger;
use App\Packages\Providers\FsspWantedInformation;
use Throwable;

/**
 * Нахождение в местном розыске
 *
 * Class FsspWantedCheckingService
 * @package App\Packages\Services
 */
class FsspWantedCheckingService
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
        $checkingItem = $this->getCheckingList($this->app, CheckingList::ITEM_FIND_FSSP_WANTED);

        if ($checkingItem->status === Constants::CHECKING_STATUS_SUCCESS) {
            return true;
        }

        $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_PROCESSING);

        try {

           $this->logger->info("Старт поиска местный розыск", $this->app->toArray());

            FsspWanted::where('app_id', $this->app->id)->delete();
            $information = (new FsspWantedInformation($this->app, $this->logger))->check();
            $item = new FsspWanted();
            $item->app_id = $this->app->id;
            if($information->getStatusResult()) {
                if(is_array($information->getResult())) {
                    $item->result = "В розыске";
                    $item->city_birth = $information->getResult()[2];
                    $item->name_organ = $information->getResult()[3];
                    $item->contact_name_organ = $information->getResult()[4];
                    $item->name_organ_wanted = $information->getResult()[5];
                    $item->article_deal = $information->getResult()[6];
                } else {
                    $item->result = $information->getResult();
                }
                //отмечаем что проверка проведена успешно
                $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_SUCCESS);
            } else {
                $item->error_message = $information->getResult();
                $this->setError($checkingItem, $item->error_message);
            }

            $item->save();

           $this->logger->info("Поиск местный розыск завершен", $item->toArray());

            return true;

        } catch(Throwable $e) {
           $this->logger->error("error: {$e->getMessage()}, {$e->getTraceAsString()}");
            $this->setError($checkingItem, $e->getMessage());
        }

        return false;
    }
}
