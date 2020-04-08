<?php
/**
 * Created by PhpStorm.
 * User: lodar
 * Date: 26.07.16
 * Time: 1:04
 */

namespace App\Packages\Moneta\request;


use App\Packages\Moneta\types\MonetaOperationInfo;

class PaymentRequest
{
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

    public $paymentPassword = null;

    public $clientTransaction = null;

    public function setClientTransaction($value) {
        $this->clientTransaction = "p{$value}";
    }

}