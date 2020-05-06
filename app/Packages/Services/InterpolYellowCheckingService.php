<?php

namespace App\Packages\Services;


use App\App;
use App\CheckingList;
use App\InterpolYellow;
use App\Packages\Constants;
use App\Packages\Loggers\CustomLogger;
use App\Packages\Providers\InterpolYellowInformation;
use Throwable;

/**
 * Сервис для проверки интерпол желтые карточки
 *
 * Class InterpolYellowCheckingService
 * @package App\Packages\Services
 */
class InterpolYellowCheckingService
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
        $checkingItem = $this->getCheckingList($this->app, CheckingList::ITEM_FIND_INTERPOL_YELLOW);

        if ($checkingItem->status === Constants::CHECKING_STATUS_SUCCESS) {
            return true;
        }

        $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_PROCESSING);


        try {

            $this->logger->info("Старт поиска интерпол желтые краточки", $this->app->toArray());

            InterpolYellow::where('app_id', $this->app->id)->delete();

            $information = (new InterpolYellowInformation($this->app, $this->logger))->check();
            $item = new InterpolYellow();
            $item->app_id = $this->app->id;

            if($information->getStatusResult()) {
                $item->result = $information->getResult();
                $item->save();
                //отмечаем что проверка проведена
                $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_SUCCESS);
            } else {
                $item->error_message = $information->getResult();
                $item->save();
                $this->setError($checkingItem, $information->getResult());
            }

            $this->logger->info("Поиск интерпол желтые краточки завершен", $item->toArray());

            return true;

        } catch(Throwable $e) {
            $this->logger->error("error: {$e->getMessage()}, {$e->getTraceAsString()}");
            $this->setError($checkingItem, $e->getMessage());
        }

        return false;

    }
}
