<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-01-21
 * Time: 23:45
 */

namespace App\Packages\Providers;


use App\App;
use App\FindInn;
use App\Packages\Loggers\CustomLogger;
use GuzzleHttp\Client;

/**
 * Руководство и учредительство
 *
 * Class HonestBusinessInformation
 * @package App\Packages\Providers
 */
class HonestBusinessInformation implements IProvider
{
    use ArrayProxyInjector;

    /**
     * @var App
     */
    private $inn;
    private $logger;
    private $client;

    public function __construct(FindInn $inn, CustomLogger $logger)
    {
        $this->inn = $inn;
        $this->logger = $logger;
        $this->selectProxy();
        $this->client = new Client($this->injectProxyOptions());
    }

    /**
     * @return Result|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    function check()
    {
        $retData = new Result();

        $response = $this->client->request('POST', 'https://zachestnyibiznesapi.ru/paid/data/search', [
            'form_params' => [
                'api_key' => config('datame.za_chestnyi_biznes'),
                'string' => $this->inn->inn
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        $this->logger->info("response", $data);

        $retData->setStatusResult(true)->setResult($data['body']['docs']);

        return $retData;
    }
}