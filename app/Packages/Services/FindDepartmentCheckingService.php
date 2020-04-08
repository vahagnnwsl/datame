<?php

namespace App\Packages\Services;


use App\App;
use App\CheckingList;
use App\FindDepartment;
use App\FindDepartmentList;
use App\Packages\Constants;
use App\Packages\Loggers\CustomLogger;
use App\Packages\Providers\FindDepartmentInformation;
use Throwable;

/**
 * Сервис для проверки кода подразделения
 *
 * Class FssCheckingService
 * @package App\Packages\Services
 */
class FindDepartmentCheckingService
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
        $checkingItem = $this->getCheckingList($this->app, CheckingList::ITEM_FIND_CODE_DEPARTMENT);
        $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_PROCESSING);

        try {

            $this->logger->info("Старт проверки подразделения", $this->app->toArray());

            FindDepartment::where('app_id', $this->app->id)->delete();

            $information = (new FindDepartmentInformation($this->app, $this->logger))->check();

            $item = new FindDepartment();
            $item->app_id = $this->app->id;
            if($information->getStatusResult()) {
                $item->type = $information->getResult()['department'];
                $item->save();
                $departments = collect($information->getResult()['list'])->transform(function($lst) use ($item) {
                    return new FindDepartmentList([
                        'find_department_id' => $item->id,
                        'department_id' => $lst['id']
                    ]);
                });
                $item->branches()->saveMany($departments);
                $item->save();
                //отмечаем что проверка проведена
                $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_SUCCESS);
            } else {
                $item->error_message = $information->getResult();
                $this->setMessage($checkingItem, $item->error_message);
            }

            $this->logger->info("Поиск проверки подразделения", $information->getResult());

            return true;

        } catch(Throwable $e) {
            $this->logger->error("error: {$e->getMessage()}, {$e->getTraceAsString()}");
            $this->setError($checkingItem, $e->getMessage());

        }

        return false;
    }
}