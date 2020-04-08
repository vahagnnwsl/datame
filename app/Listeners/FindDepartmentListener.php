<?php

namespace App\Listeners;

use App\CheckingList;
use App\Events\FindDepartmentCheckingEvent;
use App\Packages\Constants;
use App\Packages\Services\FindDepartmentCheckingService;
use App\Packages\Services\TraitCheckingList;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Обработчик поиска подразделения по коду подразделения
 *
 * @package App\Listeners
 */
class FindDepartmentListener implements ShouldQueue
{
    use TraitCheckingList;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param FindDepartmentCheckingEvent $event
     * @return void
     */
    public function handle(FindDepartmentCheckingEvent $event)
    {
        //если есть код подразделения
        if(!empty($event->app->code_department))
            (new FindDepartmentCheckingService($event->app, $event->logger))->execute();
        else {
            /** @var CheckingList $checkingItem */
            $checkingItem = $this->getCheckingList($event->app, CheckingList::ITEM_FIND_CODE_DEPARTMENT);
            $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_SUCCESS);
        }
    }
}
