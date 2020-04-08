<?php

namespace Tests\Unit;

use App\App;
use App\Packages\Providers\DisqInformation;
use Carbon\Carbon;
use Tests\TestCase;

class DisqInformationTest extends TestCase
{


    /**
     * Проверка по реестру дисквалифицированных лиц
     *
     */
    public function testInnTest()
    {
        $app = new App();
        $app->lastname = "АБАБКОВА";
        $app->name = "МАРГАРИТА";
        $app->patronymic = "МИХАЙЛОВНА";
        $app->birthday = Carbon::createFromDate(1957, 12, 25);

        $checker = new DisqInformation($app);

        $response = $checker->check();
        $this->assertTrue($response->getStatusResult());

    }

}
