<?php

namespace Tests\Unit;

use App\App;
use App\Packages\Providers\InnInformation;
use Tests\TestCase;

class InnInformationTest extends TestCase
{


    /**
     * Поиск инн
     */
    public function testInn1()
    {

        $appData = new App;
        $appData->lastname = "поликарова";
        $appData->name = "евгения";
        $appData->patronymic = "борисовна";
        $appData->passport_code = "4514 621950";
        $appData->birthday = dt_parse("07.05.1969");
        $appData->date_of_issue = dt_parse("31.05.2014");

        $checker = new InnInformation($appData);

        $response = $checker->check();

        $this->assertTrue($response->getStatusResult());
        $this->assertTrue($response->getResult() == '771674738760');
    }

    public function testInn2()
    {
        $appData = new App;
        $appData->lastname = "Глинкин";
        $appData->name = "Иван";
        $appData->patronymic = "Олегович";
        $appData->passport_code = "4509 445343";
        $appData->birthday = dt_parse("31.10.1987");
        $appData->date_of_issue = dt_parse("19.12.2007");

        $checker = new InnInformation($appData);

        $response = $checker->check();

        $this->assertTrue($response->getStatusResult());
        $this->assertTrue($response->getResult() == '772982992699');
    }

    public function testInn3()
    {
        $appData = new App;
        $appData->lastname = "Логунов";
        $appData->name = "Владислав";
        $appData->patronymic = "Олегович";
        $appData->passport_code = "6512 520825";
        $appData->birthday = dt_parse("11.09.1967");
        $appData->date_of_issue = dt_parse("04.07.2013");

        $checker = new InnInformation($appData);

        $response = $checker->check();

        $this->assertTrue($response->getStatusResult());
        $this->assertTrue($response->getResult() == '665915087025');
    }

    public function testInn4()
    {
        $appData = new App;
        $appData->lastname = "Логунов";
        $appData->name = "Владислав";
        $appData->patronymic = "Олегович";
        $appData->passport_code = "6512 520825";
        $appData->birthday = dt_parse("11.09.1967");
            $appData->date_of_issue = dt_parse("04.07.2013");

        $checker = new InnInformation($appData);

        $response = $checker->check();

        $this->assertTrue($response->getStatusResult());
        $this->assertTrue($response->getResult() == '665915087025');
    }

}
