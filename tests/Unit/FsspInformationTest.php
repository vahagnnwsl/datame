<?php

namespace Tests\Unit;

use App\App;
use App\Packages\Providers\FsspInformation;
use Carbon\Carbon;
use Tests\TestCase;

/**
 * Проверка фссп
 *
 * Class FsspInformationTest
 * @package Tests\Unit
 */
class FsspInformationTest extends TestCase
{


    public function testFssp_1()
    {
        $app = new App();
        $app->lastname = "ЛЕВШАНОВ";
        $app->name = "АЛЕКСАНДР";
        $app->patronymic = "ПЕТРОВИЧ";
        $app->birthday = Carbon::createFromDate(1964, 12, 23);
        $app->birthday = dt_parse("23.12.1964");



//        $app->lastname = "поликарова";
//        $app->name = "евгения";
//        $app->patronymic = "борисовна";
//        $app->birthday = Carbon::createFromDate(1969, 05, 07);

        $checker = new FsspInformation($app);

        $response = $checker->check();
        dd($response);

    }

    public function testFssp_2()
    {

        $app = new App();
        $app->lastname = "поликарова";
        $app->name = "евгения";
        $app->patronymic = "борисовна";
        $app->birthday = dt_parse("07.05.1969");

        $checker = new FsspInformation($app);

        $response = $checker->check();
        dd($response);
    }

    public function testFssp_3()
    {

        $app = new App();
        $app->lastname = "глинкин";
        $app->name = "иван";
        $app->patronymic = "олегович";
        $app->birthday = dt_parse("31.10.1987");

        $checker = new FsspInformation($app);

        $response = $checker->check();
        dd($response);
    }


}
