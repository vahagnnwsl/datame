<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-01-19
 * Time: 23:08
 */

namespace App\Packages\Providers;


use App\App;
use App\Department;
use App\Packages\Constants;
use App\Packages\Loggers\CustomLogger;

/**
 *
 * Поиск подразделения по коду подразделения
 * Class FindDepartmentInformation
 * @package App\Packages\Providers
 */
class FindDepartmentInformation implements IProvider
{
    /**
     * @var App
     */
    private $app;
    protected $logger;

    public function __construct(App $app, CustomLogger $logger)
    {
        $this->app = $app;
        $this->logger = $logger;
    }

    /**
     * @return Result|null
     */
    public function check()
    {
        $result = new Result();
        $this->logger->info("passport", $this->app->toArray());

        //Проверяем, правильно ли введена третья цифра кода подразделения.
        // «0» — УФМС (ныне ГУВМ);
        // «1» — паспортно-визовая служба (ПВС), либо МВД, УВД либо ГУВД;
        // «2» — ПВС района или городского уровня;
        // «3» — ПВС сельского или городского типа.
        // Иначе – код подразделения введен неверно

        $thirdNumber = intval(mb_substr($this->app->code_department, 3, 1));
        switch($thirdNumber) {
            case Constants::CODE_DEPARTMENT_GUVM:
            case Constants::CODE_DEPARTMENT_PVS:
            case Constants::CODE_DEPARTMENT_PVS_REGION:
            case Constants::CODE_DEPARTMENT_VILLAGE:
                $result->setResult([
                    'department' => $thirdNumber,
                    'list' => []
                ]);
                $result->setStatusResult(true);
                break;
            default:
                $result->setResult("Код подразделения введен неверно");
                $this->logger->error("Третья цифра кода подразделения введена не верно.");
        }

        //предыдущая проверка прошла,
        //ищем по коду подразделения устанавливаем подразделение, выдавшее паспорт
        if($result->getStatusResult()) {
            $departments = Department::where('code', $this->app->code_department)->get();
            if($departments->isNotEmpty()) {
                $lst['list'] = $departments->transform(function(Department $item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name
                    ];
                })->toArray();
                $lst['department'] = $result->getResult()['department'];
                $result->setResult($lst);
            }
        }

        $this->logger->info("check", $result->toArray());

        return $result;
    }
}