<?php
/**
 * Created by PhpStorm.
 * User: lodar
 * Date: 12.07.16
 * Time: 21:10
 */

namespace App\Packages\Moneta;

use App\Packages\Loggers\ApiLog;
use App\Packages\Moneta\request\FindOperationsListByCTIDRequest;
use App\Packages\Moneta\request\FindServiceProviderByIdRequest;
use App\Packages\Moneta\request\GetNextStepRequest;
use App\Packages\Moneta\request\PaymentBatchRequest;
use App\Packages\Moneta\request\PaymentRequest;
use App\Packages\Moneta\request\VerifyPaymentBatchRequest;
use App\Packages\Moneta\response\GetOperationDetailsByIdResponse;

class MonetaSoapClient extends MonetaWebServiceConnector
{

    public $wsdlAttr;
    public $wsdl;
    public $user;
    public $password;

    public function __construct($type = 1)
    {
        $this->user = config('datame.moneta.user');
        $this->password = config('datame.moneta.password');
        $this->wsdl = config('datame.moneta.wsdl');
        $this->wsdlAttr = config('datame.moneta.wsdl-attr');

        switch($type) {
            case 1:
                parent::__construct($this->wsdlAttr, $this->user, $this->password);
                break;
            case 2:
                parent::__construct($this->wsdl, $this->user, $this->password);
                break;
        }
    }

    public function getNSoapClient()
    {
        return $this->client;
    }

    protected function init($wsdl, $userName, $password)
    {
        parent::init($wsdl, $userName, $password);
    }

    public function getAllFunctions()
    {
        return $elements = $this->client->__getFunctions();
    }

    public function getAllTypes()
    {
        return $elements = $this->client->__getTypes();
    }

    public function GetServiceProviders()
    {
        return $this->call('GetServiceProviders', []);
    }

    public function FindServiceProviderById(FindServiceProviderByIdRequest $request)
    {
        return $this->call('FindServiceProviderById', [$request]);
    }

    public function GetNextStep(GetNextStepRequest $request)
    {

        $this->client->setChangeVersion(true);
        $ret = $this->call('GetNextStep', [$request]);
        ApiLog::newInstance()->info("GetNextStep response: " . $this->GetLastResponse());
        return $ret;
    }

    public function Payment(PaymentRequest $request)
    {
        $ret = $this->call('Payment', [$request]);
        ApiLog::newInstance()->info("Payment response: " . $this->GetLastResponse());
        return $ret;
    }

    public function PaymentBatch(PaymentBatchRequest $request)
    {
        $ret = $this->call('PaymentBatch', [$request]);
        ApiLog::newInstance()->info("PaymentBatchRequest request: " . $this->GetLastRequest());
        ApiLog::newInstance()->info("PaymentBatchRequest response: " . $this->GetLastResponse());
        return $ret;
    }

    public function VerifyPaymentBatch(VerifyPaymentBatchRequest $request)
    {
        $ret = $this->call('VerifyPaymentBatch', [$request]);
        ApiLog::newInstance()->info("VerifyPaymentBatchRequest request: " . $this->GetLastRequest());
        ApiLog::newInstance()->info("VerifyPaymentBatchRequest response: " . $this->GetLastResponse());
        return $ret;
    }

    /**
     * Получение данных операции по номеру операции.
     * @param int $request
     *
     * @return GetOperationDetailsByIdResponse
     */
    public function GetOperationDetailsById($request)
    {
        $request = array($request);
        return $this->call("GetOperationDetailsById", $request);
    }

    /**
     * Получение списка операций по номеру счета и внешнему идентификатору операции.
     * @param FindOperationsListByCTIDRequest $request
     * @return mixed
     */
    public function FindOperationsListByCTID(FindOperationsListByCTIDRequest $request) {
        return $this->call("FindOperationsListByCTID", [$request]);
    }

    public function GetLastRequest()
    {
        return $this->client->__getLastRequest();
    }

    public function GetLastResponse()
    {
        return $this->client->__getLastResponse();
    }

    public function GetLastRequestHeaders()
    {
        return $this->client->__getLastRequestHeaders();
    }

    public function GetLastResponseHeaders()
    {
        return $this->client->__getLastResponseHeaders();
    }

    public function getRequest()
    {
        return $this->client->getRequest();
    }

    public function getResponse()
    {
        return $this->client->getResponse();
    }
}