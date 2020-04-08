<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 5/25/18
 * Time: 11:01 AM
 */

namespace App\Packages\Moneta\types;


/**
 * Тип, описывающий состояние операции в системе МОНЕТА.РУ.
 * MONETA.RU verification status of a transaction.
 *
 */
class VerifyTransferResponseType
{

    /**
     * @var PaymentTransaction
     */
    protected $transactionRequest;
    protected $index = null;

    /**
     * Если поле равно true, то данная операция может быть проведена в системе МОНЕТА.РУ
     * If this element is set to true, MONETA.RU can process the specified transaction.
     *
     *
     * @var boolean
     */
    protected $isTransactionValid = false;
    /**
     * Описание текущего статуса операции.
     * Description of the current transaction status.
     *
     *
     * @var string
     */
    protected $description = null;
    /**
     * Если операция может быть проведена, то в данном поле содержатся детали операции. Иначе - это поле пустое.
     * If the transaction is valid, this element includes additional information about the transaction. If MONETA.RU cannot process the transaction, this element is empty.
     *
     *
     * @var ForecastTransactionResponseType
     */
    protected $forecast = null;
    /**
     * Поле содержит код ошибки. Поле заполнено только тогда, когда есть описание ошибки в поле description.
     * Для получения этого поля в запросе необходимо выставлять атрибут version равный или больше VERSION_2.
     * Коды ошибок совпадают со значением элемента faultDetail (смотрите описание для этого элемента).
     * If an error occurs when processing a transaction, the errorCode element contains an error code.
     * MONETA.RU returns this element only if you set the version attribute of your request to VERSION_2 or higher.
     * See the faultDetail element for the error code descriptions.
     *
     *
     * @var string
     */
    protected $errorCode = null;
    /**
     * Дополнительные свойства операции.
     * Для получения этого поля в запросе необходимо выставлять атрибут version равный или больше VERSION_2.
     * Если в ответе придет поле с ключом paymentPasswordChallengeRequired и значением true, то перед проведением операции следует сделать запрос "GetAccountPaymentPasswordChallengeRequest", который вернет строку запроса для платежного пароля. Это значение необходимо использовать в поле "paymentPasswordChallenge" в запросах на проведение операции (например: "PaymentRequest", "TransferRequest", "AuthoriseTransactionRequest" и т.д.).
     * Additional transaction details.
     * MONETA.RU returns operationInfo only if you set the version attribute of your request to VERSION_2 or higher.
     * If the response includes the paymentPasswordChallengeRequired key with the true value in the attribute element, you must call GetAccountPaymentPasswordChallengeRequest for the payer's account to retrieve the paymentPasswordChallenge element. This element is used in such requests as PaymentRequest, TransferRequest, AuthoriseTransactionRequest.
     *
     *
     * @var OperationInfo
     */
    protected $operationInfo = null;

    /**
     * @return bool
     */
    public function isTransactionValid()
    {
        return $this->isTransactionValid;
    }

    /**
     * @param bool $isTransactionValid
     * @return VerifyTransferResponseType
     */
    public function setIsTransactionValid($isTransactionValid)
    {
        $this->isTransactionValid = $isTransactionValid;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return VerifyTransferResponseType
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return ForecastTransactionResponseType
     */
    public function getForecast()
    {
        return $this->forecast;
    }

    /**
     * @param ForecastTransactionResponseType $forecast
     * @return VerifyTransferResponseType
     */
    public function setForecast($forecast)
    {
        $this->forecast = $forecast;
        return $this;
    }

    /**
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param string $errorCode
     * @return VerifyTransferResponseType
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
        return $this;
    }

    /**
     * @return OperationInfo
     */
    public function getOperationInfo()
    {
        return $this->operationInfo;
    }

    /**
     * @param OperationInfo $operationInfo
     * @return VerifyTransferResponseType
     */
    public function setOperationInfo($operationInfo)
    {
        $this->operationInfo = $operationInfo;
        return $this;
    }

    /**
     * @return null
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param null $index
     * @return VerifyTransferResponseType
     */
    public function setIndex($index)
    {
        $this->index = $index;
        return $this;
    }

    /**
     * @return PaymentTransaction
     */
    public function getTransactionRequest()
    {
        return $this->transactionRequest;
    }

    /**
     * @param PaymentTransaction $transactionRequest
     * @return VerifyTransferResponseType
     */
    public function setTransactionRequest(PaymentTransaction $transactionRequest)
    {
        $this->transactionRequest = $transactionRequest;
        return $this;
    }


}