<?php

namespace Tests\Unit;

use App\App;
use App\Packages\Loggers\ApiLog;
use App\Packages\Providers\PassportInformation;
use Carbon\Carbon;
use Tests\TestCase;

class PassportInformationTest extends TestCase
{

    /**
     * Проверка паспорта по всем условиям.
     *
     * @return void
     */
    public function test1997Test()
    {
        $logger = ApiLog::staticInstance();
        $checker = new PassportInformation(new App(), $logger);

        //проверка действительности паспорта по дате
        $this->assertTrue($checker->isLessTo1997(Carbon::createFromDate(1997, 1, 1)));
        $this->assertTrue(!$checker->isLessTo1997(Carbon::createFromDate(2001, 1, 1)));
    }

    public function testDateIssueTest()
    {
        $logger = ApiLog::staticInstance();
        $checker = new PassportInformation(new App(), $logger);

        //проверка состояния выдачи паспорта
        $this->assertTrue(
            $checker->checkDateIssue(
                dt_parse("12.09.2006"), dt_parse("07.11.2006")
            )['status']  == 'Паспорт выдан вовремя'
        );

        $this->assertTrue(
            $checker->checkDateIssue(
                dt_parse("19.07.2017"), dt_parse("10.07.2017")
            )['status']  == 'Произведена внеплановая замена паспорта'
        );

        $this->assertTrue(
            $checker->checkDateIssue(
                dt_parse("24.06.2015"), dt_parse("24.06.2014")
            )['status']  == 'Произведена внеплановая замена паспорта'
        );

        $this->assertTrue(
            $checker->checkDateIssue(
                Carbon::createFromDate(2014, 8, 24), Carbon::createFromDate(2014, 6, 24)
            )['status']  == 'Паспорт выдан вовремя'
        );

        $this->assertTrue(
            $checker->checkDateIssue(
                Carbon::createFromDate(2014, 8, 24), Carbon::now()->addDay(10)
            )['status']  == 'Паспорт должен быть заменен в близжайшее время'
        );
        $this->assertTrue(
            $checker->checkDateIssue(
                Carbon::createFromDate(2014, 8, 24), Carbon::now()->addDay(40)
            )['status'] == 'Паспорт не действительный - истек срок действия'
        );
    }

    public function testValidDateIssue() {
        $appData = new App();
        $checker = new PassportInformation($appData, ApiLog::staticInstance());

        $appData->date_of_issue = dt_parse("20.02.1997");
        $appData->passport_code = "1197111111";

        $this->assertTrue($checker->isValidDateIssue());


        $appData->date_of_issue = dt_parse("20.02.2004");
        $appData->passport_code = "1100111111";

        $this->assertFalse($checker->isValidDateIssue());
    }

    public function testCheckTest()
    {

        $appData = new App();
        $appData->birthday = dt_parse("24.6.1994");
        $appData->passport_code = "3212 204286";
        $appData->date_of_issue = dt_parse("20.09.2012");
        $appData->code_department = "420 022";

        $checker = new PassportInformation($appData, ApiLog::staticInstance());
        dd($checker->check()['passport_date_replace']);

        $this->assertTrue(true);

    }

    public function testCheckTest_1()
    {

        $appData = new App();
        $appData->birthday = dt_parse("10.07.1972");
        $appData->passport_code = "6317 401003";
        $appData->date_of_issue = dt_parse("19.07.2017");
        $appData->code_department = "640 044";

        $checker = new PassportInformation($appData, ApiLog::staticInstance());
        dd($checker->check()['passport_date_replace']);

        $this->assertTrue(true);
    }

    public function testCheckTest_2()
    {
        $appData = new App();
        $appData->birthday = dt_parse("18.06.1983");
        $appData->passport_code = "4507 447001";
        $appData->date_of_issue = dt_parse("16.06.2004");
        $appData->code_department = "772 040";

        $checker = new PassportInformation($appData, ApiLog::staticInstance());
        dd($checker->check()['passport_date_replace']);

        $this->assertTrue(true);
    }




}
