<?php

namespace App\Packages\Services;


use App\App;
use App\CheckingList;
use App\InterpolRed;
use App\Packages\Constants;
use App\Packages\Loggers\CustomLogger;
use App\Packages\Providers\InterpolRedInformation;
use Throwable;

/**
 * Сервис для проверки интерпол красные карточки
 *
 * Class InterpolRedCheckingService
 * @package App\Packages\Services
 */
class InterpolRedCheckingService
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
        $checkingItem = $this->getCheckingList($this->app, CheckingList::ITEM_FIND_INTERPOL_RED);
        $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_PROCESSING);

        try {

            $this->logger->info("Старт поиска интерпол красные краточки", $this->app->toArray());

            InterpolRed::where('app_id', $this->app->id)->delete();
            $information = (new InterpolRedInformation($this->app, $this->logger))->check();
            $item = new InterpolRed();
            $item->app_id = $this->app->id;
            if($information->getStatusResult()) {
                $item->result = $information->getResult();

                //отмечаем что проверка проведена
                $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_SUCCESS);
            } else {
                $item->error_message = $information->getResult();
                $this->setError($checkingItem, $item->error_message);
            }

            $item->save();

            $this->logger->info("Поиск интерпол красные краточки завершен", $item->toArray());

            return true;

        } catch(Throwable $e) {
            $this->logger->error("error: {$e->getMessage()}, {$e->getTraceAsString()}");
            $this->setError($checkingItem, $e->getMessage());
        }

        return false;

    }
}