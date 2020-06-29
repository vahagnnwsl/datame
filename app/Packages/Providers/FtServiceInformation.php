<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-01-21
 * Time: 23:45
 */

namespace App\Packages\Providers;

use App\App;
use App\CheckingList;
use App\FindInn;
use App\FtService;
use App\Packages\Constants;
use App\Packages\Loggers\CustomLogger;
use App\Packages\Services\TraitCheckingList;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Throwable;

/**
 * Нахождение в местном розыске
 *
 * Class MvdWantedInformation
 * @package App\Packages\Providers
 */
class FtServiceInformation implements IProvider
{
    use ArrayProxyInjector;
    use TraitCheckingList;

    private $inn;
    private $logger;
    private $client;


    public function __construct(FindInn $inn, CustomLogger $logger)
    {
        $this->inn = $inn;
        $this->logger = $logger;
        $this->selectProxy();
        $guzzleOptions = [
            'base_uri' => 'https://statusnpd.nalog.ru',
            'headers' =>
                [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ]

        ];
        $this->client = new Client($this->injectProxyOptions($guzzleOptions));
    }


    public function check()
    {


        $inn = $this->inn->inn;
        $requestDate = $this->inn->created_at->format('Y-m-d');

        $checkingItem = $this->getCheckingList($this->inn->app, CheckingList::ITEM_FIND_FT_SERVICE);


        if ($checkingItem->status === Constants::CHECKING_STATUS_SUCCESS) {
            return true;
        }


        $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_PROCESSING);

        try {
            $resp = $this->client->post('/api/v1/tracker/taxpayer_status', [
                'json' => [
                    'inn' => $inn,
                    'requestDate' => $requestDate
                ]
            ]);
            $resp = \GuzzleHttp\json_decode($resp->getBody());
            FtService::create([
                'inn_id' => $this->inn->id,
                'status' => $resp->status,
                'message' => $resp->message
            ]);
            $this->setIsCheckedCheckingList($checkingItem, Constants::CHECKING_STATUS_SUCCESS);


         return true;

        } catch (GuzzleException $e) {
            $this->setError($checkingItem, $e->getMessage());
        }catch(Throwable $e) {
            $this->setError($checkingItem, $e->getMessage());
        }

        return false;
    }

}
