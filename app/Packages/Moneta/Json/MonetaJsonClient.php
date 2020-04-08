<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 2019-02-21
 * Time: 16:32
 */

namespace App\Packages\Moneta\Json;


use App\Packages\Moneta\Json\Request\BaseRequest;
use GuzzleHttp\Client;

class MonetaJsonClient
{
    protected $client;
    protected $url;
    protected $response;
    protected $request;
    protected $statusCode;

    public function __construct()
    {
        $this->url = "https://service.moneta.ru/services";
        $this->client = new Client();
    }

    /**
     * @param BaseRequest $request
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function execute(BaseRequest $request)
    {
        $this->request = $request->getFullRequest();
        $response = $this->client->request("POST", "https://service.moneta.ru/services", [
            'json' => $this->request
        ]);
        $this->response = $response->getBody()->getContents();
        $this->statusCode = $response->getStatusCode();
        return json_decode($this->response);
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}