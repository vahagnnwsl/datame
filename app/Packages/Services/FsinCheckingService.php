<?php

namespace App\Packages\Services;


use App\App;
use App\CheckingList;
use App\FindFsin;
use App\FindFssp;
use App\Packages\Constants;
use App\Packages\Loggers\CustomLogger;
use App\Packages\Providers\FsinInformation;
use App\Packages\Providers\FsspInformation;
use Throwable;

/**
 * Сервис для проверки фссп
 *
 * Class FssCheckingService
 * @package App\Packages\Services
 */
class FsinCheckingService
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
        $checkingItem = $this->getCheckingList($this->app, CheckingList::ITEM_FIND_FSIN);

        if ($checkingItem->status === Constants::CHECKING_STATUS_SUCCESS) {
            return true;
        }



        $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_PROCESSING);

        try {
            $this->logger->info("Старт поиска фсин", $this->app->toArray());
            FindFsin::where('app_id', $this->app->id)->delete();
            $information = (new FsinInformation($this->app, $this->logger))->check();

            $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_SUCCESS);

            FindFsin::create($information->getResult());

            return true;

        }catch (\Exception $e) {
            $this->logger->error("error: {$e->getMessage()}, {$e->getTraceAsString()}");
            $this->setError($checkingItem, $e->getMessage());
        }

        return false;
    }
}
