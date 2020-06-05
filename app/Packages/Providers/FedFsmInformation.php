<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-03-06
 * Time: 00:34
 */

namespace App\Packages\Providers;

use App\App;
use App\Packages\Loggers\CustomLogger;

/**
 * Нахождение в списках террористов и экстремистов
 *
 * Class FedFsmInformation
 * @package App\Packages\Providers
 */
class FedFsmInformation implements IProvider
{

    use CurlProxyInjector;

    /**
     * @var App
     */
    private $app;
    private $logger;

    public function __construct(App $app, CustomLogger $logger)
    {
        $this->app = $app;
        $this->logger = $logger;
        $this->selectProxy();
    }

    /**
     * @return Result
     */
    function check()
    {
        $retData = new Result();

        if(!file_exists(storage_path('tmp')))
            mkdir(storage_path('tmp'), 0777, true);

        $session = date('YmdHis') . rand(100000, 999999);
        $cookies_file = storage_path('/tmp/cookies' . $session . '.txt');

        $ch2 = curl_init();
        $ch2 = $this->injectProxyOptions($ch2);
        $curl_params2 = array(
            CURLOPT_URL => 'http://www.fedsfm.ru/TerroristSearchAutocomplete?query=' . $this->app->lastname . '+' . $this->app->name . '+' . $this->app->patronymic . '*%2C+' . $this->app->birthday->format('d.m.Y'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_COOKIEFILE => $cookies_file,
            CURLOPT_COOKIEJAR => $cookies_file,
            CURLOPT_HTTPHEADER => array("Cache-Control: no-cache"),
            CURLOPT_TIMEOUT => 10

        );
        curl_setopt_array($ch2, $curl_params2);
        $result2 = curl_exec($ch2);
        $result2 = json_decode($result2, true);

        if($result2['suggestions']) {
            $suspect = $result2['suggestions'][0];
            $content = array("rowIndex" => 0, "pageLength" => 50, "searchText" => "$suspect");

            $ch3 = curl_init();
            $ch3 = $this->injectProxyOptions($ch3);
            $curl_params3 = array(
                CURLOPT_URL => 'http://www.fedsfm.ru/TerroristSearch',
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $content,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_HTTPHEADER => array("Cache-Control: no-cache"),
                CURLOPT_TIMEOUT => 10

            );
            curl_setopt_array($ch3, $curl_params3);
            $result3 = curl_exec($ch3);
            $result3 = json_decode($result3, true);

            $retData->setStatusResult(true);

            $info = explode(",", $result3['data'][0]['FullName']);

            $retData->setResult([
                'status' => $result3['data'][0]['StatusName'],
                'full_name' => trim($info[0]),
                'city_birth' => trim(str_replace(';', '',  $info[2])),//город рождения
                'type_name' => $result3['data'][0]['TerroristTypeName']
            ]);

        } else {
            $retData->setStatusResult(true);
            $retData->setResult("В розыске отсутствует");
        }

        return $retData;
    }
}