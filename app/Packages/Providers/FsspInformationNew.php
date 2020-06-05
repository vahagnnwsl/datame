<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-01-21
 * Time: 23:45
 */

namespace App\Packages\Providers;

use App\App;
use App\Packages\Loggers\CustomLogger;
use GuzzleHttp\Client;
use Carbon\Carbon;
use GuzzleHttp\RequestOptions;

/**
 * Нахождение в местном розыске
 *
 * Class MvdWantedInformation
 * @package App\Packages\Providers
 */
class FsspInformationNew implements IProvider
{
    use ArrayProxyInjector;

    private $app;
    private $logger;
    private $client;
    private $token = 'f8ZWWnmHAw7p';
    private $url = 'https://api-ip.fssprus.ru/api/v1.0';
    private $regions;

    public function __construct(App $app, CustomLogger $logger)
    {
        $this->app = $app;
        $this->logger = $logger;
        $this->selectProxy();
        $this->client = new Client($this->injectProxyOptions());
        $this->regions = config('fssp_regions');
    }


    public function check()
    {
        $retData = new Result();
        $this->storeTask();
        $task_id = 'ca6c9118-f060-4716-bc16-409c8930a08d';


        $this->getResult($task_id);
    }


    public function storeTask()
    {
        $firstname = $this->app->name;
        $lastname = $this->app->lastname;
        $patronymic = $this->app->patronymic;
        $birthdate = Carbon::parse($this->app->birthday, 'UTC')->format('d.m.Y');


        $request_data = [];

        foreach ($this->regions as $region) {
            $request_data[] = [
                "type" => 1,
                "params" => [
                    "firstname" => $firstname,
                    "lastname" => $lastname,
                    "secondname" => $patronymic,
                    "region" => $region['cod'],
                    "birthdate" => $birthdate
                ],
            ];
        }


        $response = $this->client->request('POST', $this->url . '/search/group',
            [
                RequestOptions::JSON => [
                    'token' => $this->token,
                    'request' => $request_data
                ]
            ]);

        dd(json_decode($response->getBody()));
    }


    function getResult($task_id)
    {
        $urlStatus = $this->url . '/status';
        $urlResult = $this->url . '/result';

        $resp = $this->client->request('GET', $urlStatus, [
            'query' => [
                'token' => $this->token,
                'task' => $task_id,
            ]
        ]);

        dd(json_decode($resp->getBody()));

    }
}
