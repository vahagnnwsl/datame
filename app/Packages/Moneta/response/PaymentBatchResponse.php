<?php

namespace App\Packages\Moneta\response;

use App\Packages\Moneta\request\PaymentBatchRequest;
use App\Packages\Moneta\types\MonetaKeyValueAttribute;
use App\Packages\Moneta\types\MonetaOperationInfo;
use Illuminate\Support\Collection;


/**
 * Ответ на запрос перевода денежных средств в пакетном режиме.
 * Response to a request for transferring money to another account in batch mode.
 *
 */
class PaymentBatchResponse
{

    /**
     * Детали операций, либо описание ошибок. Порядок соответствует набору операций, переданных в VerifyPaymentBatchRequest.
     * Either information about transactions or error descriptions in the same order as in the VerifyPaymentBatch request.
     *
     *
     * @var Collection
     */
    public $transaction;

    public function __construct()
    {
        $this->transaction = new Collection();
    }

    /**
     * Детали операций, либо описание ошибок. Порядок соответствует набору операций, переданных в VerifyPaymentBatchRequest.
     * Either information about transactions or error descriptions in the same order as in the VerifyPaymentBatch request.
     *
     *
     * @param MonetaOperationInfo $item
     *
     * @return void
     */
    public function addTransaction(MonetaOperationInfo $item)
    {
        $this->transaction->push($item);
    }


    public function parse($data)
    {

//        dd($data);
        if(is_array($data->transaction)) {
            dump("is_array: " . is_array($data->transaction));
            foreach($data->transaction as $index => $transact) {
                dump("index");
                dump($index);
                dump("trans");
                dump($transact);
                if(isset($transact->error))
                    $this->addTransaction((new MonetaOperationInfo())->setIndex($index)->setError($transact->error));
                else
                    $this->addTransaction($this->buildResponseType($transact->transaction, $index));
            }
        } else {
            dump("trans");
            dump($data->transaction);
            if(isset($data->transaction->error))
                $this->addTransaction((new MonetaOperationInfo())->setIndex(0)->setError($data->transaction->error));
            else
                $this->addTransaction($this->buildResponseType($data->transaction->transaction, 0));
        }
        return $this;

    }

    /**
     * @return Collection
     */
    public function getTransaction()
    {
        return $this->transaction;
    }


    /**
     * @param $transact
     * @param $index
     * @return MonetaOperationInfo
     */
    protected function buildResponseType($transact, $index)
    {
        dump($transact);
        dump($transact);
        $info = new MonetaOperationInfo();
        $info->setIndex($index);
        $info->setId($transact->id);

        foreach($transact->attribute as $item) {
            $attr = new MonetaKeyValueAttribute($item->key, $item->value);
            $info->addAttribute($attr);
        }
        return $info;
    }

    /**
     * Связывание результатат проверки с запросом платежа
     * @param PaymentBatchRequest $request
     */
    public function relateRequestWithTransaction(PaymentBatchRequest $request)
    {
        $this->getTransaction()->each(function(MonetaOperationInfo $item) use ($request) {
            $item->setTransactionRequest($request->getTransactionByIndex($item->getIndex()));
        });
    }

}