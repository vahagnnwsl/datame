<?php

namespace App\Packages\Services;


use App\CheckingList;
use App\FindFssp;
use App\FindInn;
use App\FtService;
use App\Packages\Constants;
use App\Packages\Loggers\CustomLogger;
use App\Packages\Providers\FtServiceInformation;
use Throwable;

/**
 * Сервис для проверки фссп
 *
 * Class FssCheckingService
 * @package App\Packages\Services
 */
class FtsCheckingService
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

        $checkingItem = $this->getCheckingList($this->inn->app, CheckingList::ITEM_FIND_FT_SERVICE);

        if ($checkingItem->status === Constants::CHECKING_STATUS_SUCCESS) {
            return true;
        }

        $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_PROCESSING);

        try {

            $this->logger->info("Старт поиска ФНС", $this->inn->toArray());

            FtService::where('inn_id', $this->inn->id)->delete();

            $information = (new FtServiceInformation($this->inn, $this->logger))->check();
            if($information) {

                $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_SUCCESS);

            } else {
                $checkingItem->message = "Не удалось получить ответ от ФНС по техническим причинам";
                $this->setError($checkingItem, $checkingItem->message);
            }
            return true;

        } catch(Throwable $e) {
            $this->setError($checkingItem, $e->getMessage());
        }

        return false;
    }
}
