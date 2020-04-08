<?php

namespace Tests\Unit;

use App\App;
use App\Packages\Providers\InterpolRedInformation;
use Tests\TestCase;

/**
 * Поиск интерпол красные карточки
 *
 * Class InterpolRedInformationTest
 * @package Tests\Unit
 */
class InterpolRedInformationTest extends TestCase
{

    public function testSearchingSuccessTest()
    {
        $app = new App();
        $app->lastname = "магуев";
        $app->name = "магомед";

        $checker = new InterpolRedInformation($app);

        $response = $checker->check();

        $this->assertTrue($response->getStatusResult());
        $this->assertTrue($response->getResult() == "В розыске");
    }

    public function testSearchingFailTest()
    {
        $app = new App();
        $app->lastname = "магуев2";
        $app->name = "магомед2";

        $checker = new InterpolRedInformation($app);

        $response = $checker->check();

        $this->assertTrue($response->getStatusResult());
        $this->assertTrue($response->getResult() == "В розыске отсутствует");
    }

}
