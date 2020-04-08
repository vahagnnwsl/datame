<?php

namespace Tests\Unit;

use App\FindInn;
use App\Packages\Providers\HonestBusinessInformation;
use Tests\TestCase;

class HonestBusinessInformationTest extends TestCase
{


    /**
     * Проверка лица на банкротство
     *
     */
    public function testInnTest()
    {
        $inn = new FindInn();
        $inn->inn = "772982992699";

        $checker = new HonestBusinessInformation($inn);

        $response = $checker->check();

        dd($response);

    }

}
