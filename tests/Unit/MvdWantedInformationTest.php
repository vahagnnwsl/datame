<?php

namespace Tests\Unit;

use App\App;
use App\Packages\Providers\MvdWantedInformation;
use Carbon\Carbon;
use Tests\TestCase;

/**
 * Поиск в федеральном розыске
 *
 * Class MvdWantedInformationTest
 * @package Tests\Unit
 */
class MvdWantedInformationTest extends TestCase
{

    public function testSearchingSuccessTest()
    {

        $app = new App();
        $app->lastname = "ТИМОШЕНКО";
        $app->name = "ОЛЕГ";
        $app->patronymic = "ЕВГЕНЬЕВИЧ";
        $app->birthday = Carbon::createFromDate(1967, 1, 10);


        $checker = new MvdWantedInformation($app);

        $response = $checker->check();
        dump($response);
        $this->assertTrue($response->getStatusResult());
        $this->assertTrue($response->getResult() == "Найдено в розыске записей: 2");
    }

    public function testSearchingFailTest()
    {

        $app = new App();
        $app->lastname = "ТИМОШЕНКО2";
        $app->name = "ОЛЕГ";
        $app->patronymic = "ЕВГЕНЬЕВИЧ";
        $app->birthday = Carbon::createFromDate(1967, 1, 10);


        $checker = new MvdWantedInformation($app);

        $response = $checker->check();
        $this->assertTrue($response->getStatusResult());
        $this->assertTrue($response->getResult() == "В розыске отсутствует");
    }

    public function test2SearchingFailTest()
    {

        $app = new App();
        $app->lastname = "АБАБКОВА";
        $app->name = "МАРГАРИТА";
        $app->patronymic = "МИХАЙЛОВНА";
        $app->birthday = Carbon::createFromDate(1925, 12, 25);


        $checker = new MvdWantedInformation($app);

        $response = $checker->check();
        dd($response);
    }

}
