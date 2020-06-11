<?php


namespace App\Packages\Services;


use App\App;
use App\CheckingList;
use App\CustomData;
use App\FindCustomData;
use App\Packages\Constants;
use App\Packages\Loggers\CustomLogger;

/**
 * Проверка в базе данных
 *
 * Class CustomDataCheckingService
 * @package App\Packages\Services
 */
class CustomDataCheckingService
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
        $checkingItem = $this->getCheckingList($this->app, CheckingList::ITEM_FIND_CUSTOM_DATA);


        if ($checkingItem->status === Constants::CHECKING_STATUS_SUCCESS) {
            return true;
        }

        $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_PROCESSING);
        $this->logger->info("Старт поиска в нашей базе данных", $this->app->toArray());

        FindCustomData::where('app_id', $this->app->id)->delete();
        $fullName = $this->app->lastname . " " . $this->app->name  . " " . $this->app->patronymic;
        $birthday = $this->app->birthday;
        $customData = CustomData::whereFullName($fullName)->whereBirthday($birthday)->first();
        if (!$customData) {
            $this->logger->error("Ничего не найдена в базе данных");
            $this->setMessage($checkingItem, "Ничего не найдена в базе данных");
            $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_SUCCESS);
            return false;
        }

        $findCustomData = new FindCustomData();
        $findCustomData->app_id = $this->app->id;
        $findCustomData->additional = $customData->additional;
        $findCustomData->save();
        $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_SUCCESS);

        return true;
    }
}