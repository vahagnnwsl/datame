<?php

namespace Tests\Unit;

use App\App;
use App\Packages\Providers\InterpolYellowInformation;
use Tests\TestCase;

class InterpolYellowInformationTest extends TestCase
{


    public function testSearchingSuccessTest()
    {
        $app = new App();
        $app->lastname = "абдаллах";
        $app->name = "амира";

        $checker = new InterpolYellowInformation($app);

        $response = $checker->check();

        $this->assertTrue($response->getStatusResult());
        $this->assertTrue($response->getResult() == "В розыске");
    }

    public function testSearchingFailTest()
    {
        $app = new App();
        $app->lastname = "абдаллах2";
        $app->name = "амира";

        $checker = new InterpolYellowInformation($app);

        $response = $checker->check();

        $this->assertTrue($response->getStatusResult());
        $this->assertTrue($response->getResult() == "В розыске отсутствует");
    }

}
