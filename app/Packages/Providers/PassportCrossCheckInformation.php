<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-01-21
 * Time: 23:45
 */

namespace App\Packages\Providers;


use App\App;

/**
 * Кросс-проверки паспорта
 *
 * Class InterpolRedInformation
 * @package App\Packages\Providers
 */
class PassportCrossCheckInformation implements IProvider
{
    use CurlProxyInjector;

    /**
     * @var App
     */
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->selectProxy();
    }

    /**
     * @return Result|null
     */
    function check()
    {
        $retData = new Result();

        $family = rus2translit($this->app->lastname);
        $name = rus2translit($this->app->name);

        $ch3 = curl_init();
        $ch3 = $this->injectProxyOptions($ch3);
        $curl_params3 = array(
            CURLOPT_URL => 'https://ws-public.interpol.int/notices/v1/red?name='.$family.'&forename='.$name,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST=> false,
            CURLOPT_SSL_VERIFYPEER=> 0,
            CURLOPT_FOLLOWLOCATION => 1

        );
        curl_setopt_array($ch3, $curl_params3);
        $result3 = curl_exec($ch3);
        $result3 = json_decode($result3);

        if($result3->total == 0) {
            $retData->setStatusResult(true)->setResult("В розыске отсутствует");
        } else {

            $retData->setStatusResult(true)->setResult("В розыске");
        }

        return $retData;
    }
}