<?php

namespace App\Packages\Moneta\request;

use App\Packages\Moneta\types\PaymentTransaction;


/**
 * Тип, описывающий параметры операции в запросах в пакетном режиме.
 * Transaction parameters for requests in batch mode.
 *
 */
class PaymentBatchRequestType extends EntityBatchRequestType
{

    /**
     * Набор операций, которые необходимо выполнить в одном пакете. Операции выполняются в том порядке, в котором они переданы в запросе.
     * The set of transactions that must be processed in a single batch. MONETA.RU processes transactions in the order that they are defined in the request.
     *
     *
     * @var PaymentTransaction[]
     */
    public $transaction = null;

    /**
     * Набор операций, которые необходимо выполнить в одном пакете. Операции выполняются в том порядке, в котором они переданы в запросе.
     * The set of transactions that must be processed in a single batch. MONETA.RU processes transactions in the order that they are defined in the request.
     *
     *
     * @param PaymentTransaction $item
     *
     * @return void
     */
    public function addTransaction(PaymentTransaction $item)
    {
        $this->transaction[] = $item;
    }

    public function getTransactionByIndex($index)
    {
        return $this->transaction[$index];
    }

}