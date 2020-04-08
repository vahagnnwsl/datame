<?php

namespace App\Packages\Providers;

use App\Packages\Loggers\CustomLogger;
use App\Packages\Moneta\Json\MonetaJsonClient;
use App\Packages\Moneta\Json\Request\GetNextStepRequest;
use App\Packages\Moneta\Json\Response\GetNextStepResponse;
use App\Packages\Moneta\Json\Types\ComplexItem;
use GuzzleHttp\Exception\GuzzleException;

class TaxInformation implements IProvider
{
    /**
     * @var MonetaJsonClient
     */
    protected $client;

    private $number;

    private $providerIdMoneta;
    private $logger;

    /**
     * TaxInformation constructor.
     * @param string $number ИНН
     */
    public function __construct($number, CustomLogger $logger)
    {
        $this->client = new MonetaJsonClient();
        $this->providerIdMoneta = 9118;

        $this->number = $number;
        $this->logger = $logger;
    }

    function check()
    {
        $retData = new Result();

        try {
            $this->logger->info("Поиск налогов для инн: {$this->number}");

            $data = $this->client->execute(new GetNextStepRequest($this->number));
            $response = (new GetNextStepResponse())->parse($data);
            $error = $response->isError();

            if(!is_null($error)) {
                $retData->setResult("inn [{$this->number}]: " . $error);
            } else {
                $retData->setResult($this->parseResponseTax($response->getPayments(), $this->number));
                $retData->setStatusResult(true);
            }

        } catch(GuzzleException $e) {
            //на некоторые инн, когда налогов нет, монета отвечает 400. Инн верный. Пример 772982992699
            if($e->getCode() == 400) {
                $retData->setResult([])->setStatusResult(true);
            } else {
                //ошибка обработки
                $retData->setResult($e->getMessage());
                $this->logger->error("request", $this->client->getRequest());
                $this->logger->error("response: {$this->client->getResponse()}");
            }
        }
        $this->logger->info("result: ", $retData->toArray());

        return $retData;
    }

    private function parseResponseTax($resp, $number)
    {
        $pays = [];

        /** @var ComplexItem $item */
        foreach($resp as $item) {

            $pays[] = [
                'article' => $item->article,
                'document' => $number,
                'number' => $item->id,
                'date_protocol' => $item->dateProtocol,
                'amount' => (float)$item->amount,
                'name' => $item->name,
                'inn' => $item->inn,
                'kpp' => $item->kpp,
                'okato' => $item->okato,
                'bik' => $item->bik,
                'rs' => $item->rs,
                'kbk' => $item->kbk
            ];
        }

        return $pays;
    }

}