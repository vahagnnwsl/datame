<?php

namespace App\Packages\Moneta\types;


use App\Packages\Moneta\TraitClientTransaction;

class PaymentTransaction
{
    use TraitClientTransaction;

    protected $billPartId;

    /**
     * @var string платежный пароль
     */
    public $paymentPassword;

    /**
     * @var string номер счета плательщика
     */
    public $payer;

    /**
     * @var string номер счета получателя
     */
    public $payee;

    public $amount;

    public $isPayerAmount = false;

    /**
     * @var MonetaOperationInfo
     */
    public $operationInfo = null;

    public $clientTransaction = null;

    public function setClientTransaction($bill_id, $part_id)
    {
        $this->clientTransaction = $this->generateClientTransaction($bill_id, $part_id);
    }

    /**
     * @return null
     */
    public function getClientTransaction()
    {
        return $this->clientTransaction;
    }

    public function setBillPartId($part_id)
    {
        $this->billPartId = $part_id;
    }

    /**
     * @return mixed
     */
    public function getBillPartId()
    {
        return $this->billPartId;
    }
}