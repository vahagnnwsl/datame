<?php

namespace Tests\Unit;

use App\App;
use App\Packages\Providers\FedFsmInformation;
use Carbon\Carbon;
use Tests\TestCase;

/**
 * Нахождение в списках террористов и экстремистов
 *
 * Class FedFsspInformationTest
 * @package Tests\Unit
 */
class FedFsmInformationTest extends TestCase
{


    /**
     *
     */
    public function testSearchingSuccessTest()
    {
        $app = new App();
        $app->lastname = "ахмадов";
        $app->name = "адам";
        $app->patronymic = "эмиевич";
        $app->birthday = Carbon::createFromDate(1986, 11, 20);

        $checker = new FedFsmInformation($app);


        /**
         * App\Packages\Providers\Result {
         * -status: true
         * -result: array:4 [
         *   "status" => "Действующий"
         *   "full_name" => "1132. АХМАДОВ АДАМ ЭМИЕВИЧ*"
         *   "city_birth" => "Г. МАРЫ МАРЫЙСКОЙ ОБЛАСТИ ТССР"
         *   "type_name" => "Российское физическое лицо"
         *   ]
         * }
         */
        $response = $checker->check();
        $this->assertTrue($response->getStatusResult());
        $this->assertTrue(is_array($response->getResult()));
    }

    public function testSearchingFailTest()
    {
        $app = new App();
        $app->lastname = "ахмадов";
        $app->name = "адам";
        $app->patronymic = "эмиевич2";
        $app->birthday = Carbon::createFromDate(1986, 11, 20);

        $checker = new FedFsmInformation($app);

        $response = $checker->check();

        $this->assertTrue($response->getStatusResult());
        $this->assertTrue($response->getResult() == "Отсутствует");
    }

}
