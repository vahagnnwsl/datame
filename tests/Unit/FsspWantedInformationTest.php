<?php

namespace Tests\Unit;

use App\App;
use App\Packages\Providers\FsspWantedInformation;
use Carbon\Carbon;
use Tests\TestCase;

/**
 * Поиск в местном розыске
 *
 * Class MvdWantedInformationTest
 * @package Tests\Unit
 */
class FsspWantedInformationTest extends TestCase
{

    public function testSearchingSuccessTest()
    {

        $app = new App();
        $app->lastname = "Абезгильдина";
        $app->name = "Карина";
        $app->patronymic = "Радиковна";
        $app->birthday = Carbon::createFromDate(1981, 8, 31);

        $checker = new FsspWantedInformation($app);

        $response = $checker->check();
        dump($response);
        $this->assertTrue($response->getStatusResult());
        $this->assertTrue($response->getResult() == "Найдено в розыске записей: 2");
    }

    public function testSearchingFailTest()
    {

        $app = new App();
        $app->lastname = "Абезгильдина";
        $app->name = "Карина";
        $app->patronymic = "Радиковна2222";
        $app->birthday = Carbon::createFromDate(1981, 8, 31);

        $checker = new FsspWantedInformation($app);

        $response = $checker->check();
        dump($response);
        $this->assertTrue($response->getStatusResult());
        $this->assertTrue($response->getResult() == "В розыске не числится");
    }

}
