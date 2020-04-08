<?php

namespace Tests\Unit;

use App\App;
use App\Packages\Providers\FindDepartmentInformation;
use Tests\TestCase;

/**
 * Поиск подразделения по коду подразделения
 * Class FindDepartmentInformationTest
 * @package Tests\Unit
 */
class FindDepartmentInformationTest extends TestCase
{


    public function testFind_1()
    {
        $app = new App();
        $app->code_department = "010-007";

        $result = (new FindDepartmentInformation($app))->check();

        $this->assertTrue($result->getStatusResult());
        $this->assertTrue(count($result->getResult()['list']) == 2);
    }


}
