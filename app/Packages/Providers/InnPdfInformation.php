<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-01-21
 * Time: 15:10
 */

namespace App\Packages\Providers;


use App\App;
use App\Packages\Loggers\CustomLogger;
use App\Packages\RuCaptchaProvider;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Exception\GuzzleException;
use jumper423\decaptcha\services\RuCaptcha;
use Storage;
use Vinelab\Http\Client as HttpClient;


class InnPdfInformation implements IProvider
{

    private $host = "https://egrul.nalog.ru/";

    /**
     * @var App
     */
    private $app;
    private $logger;
    /**
     * @var RuCaptchaProvider
     */
    private $ruCaptchaService;
    protected $inn;

    public function __construct($inn)
    {
        $this->inn = $inn;
        //$this->ruCaptchaService = new RuCaptchaProvider([RuCaptcha::ACTION_FIELD_LANGUAGE => 'rn']);


    }

    /**
     * @return Result|null
     */
    public function check()
    {


        $req = [
            'url' => $this->host,
            'params' => [
                'query' => $this->inn
            ],
            'headers' => [
                "Accept: application/json, text/javascript, */*; q=0.01",
                "content-type: application/x-www-form-urlencoded",
            ],

        ];


        $client = new HttpClient;
        $resp = $client->post($req);

        dd($resp);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://egrul.nalog.ru/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "query=" . $this->inn,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/x-www-form-urlencoded",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $resp = json_decode($response);
        return $resp->t;

    }

    public function download($t)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://egrul.nalog.ru/vyp-download/" . $t,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }


}
