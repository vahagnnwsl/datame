<?php

namespace Tests\Unit;

use App\FindInn;
use App\Packages\Providers\DebtorInformation;
use Tests\TestCase;

class DebtorInformationTest extends TestCase
{


    /**
     * Проверка лица на банкротство
     *
     */
    public function testInnTest()
    {
        $inn = new FindInn();
        $inn->inn = "026908863083";
//        $inn->inn = "123456789009";

        $checker = new DebtorInformation($inn);

        $response = $checker->check();


        dd($response);

    }

}
