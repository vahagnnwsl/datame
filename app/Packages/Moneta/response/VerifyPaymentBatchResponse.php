<?php

namespace App\Packages\Moneta\response;

use App\Packages\Moneta\request\VerifyPaymentBatchRequest;
use App\Packages\Moneta\types\ForecastTransactionResponseType;
use App\Packages\Moneta\types\VerifyTransferResponseType;
use Illuminate\Support\Collection;


/**
 * Ответ на запрос проверки проведения операции в системе МОНЕТА.РУ. Ответ в пакетном режиме.
 * Response to a transaction validation request. Response in batch mode.
 *
 */
class VerifyPaymentBatchResponse
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
     * @param VerifyTransferResponseType $item
     *
     * @return void
     */
    public function addTransaction(VerifyTransferResponseType $item)
    {
        $this->transaction->push($item);
    }

    public function parse($data)
    {

        if(is_array($data->transaction)) {
            foreach($data->transaction as $index => $transact) {
                $this->addTransaction($this->buildResponseType($transact, $index));
            }
        } else {
            $this->addTransaction($this->buildResponseType($data->transaction, 0));
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
     * @return VerifyTransferResponseType
     */
    protected function buildResponseType($transact, $index)
    {
        $item = (new VerifyTransferResponseType())
            ->setIsTransactionValid($transact->isTransactionValid)
            ->setIndex($index);

        if(isset($transact->description))
            $item->setDescription($transact->description);

        if(isset($transact->forecast)) {
            $item->setForecast((new ForecastTransactionResponseType())
                ->setPayerCurrency($transact->forecast->payerCurrency)
                ->setPayerAmount($transact->forecast->payerAmount)
                ->setPayerFee($transact->forecast->payerFee)
                ->setPayer($transact->forecast->payer)
                ->setPayee($transact->forecast->payee)
                ->setPayeeCurrency($transact->forecast->payeeCurrency)
                ->setPayeeAmount($transact->forecast->payeeAmount)
                ->setPayerAlias($transact->forecast->payerAlias)
                ->setPayeeAlias($transact->forecast->payeeAlias));
        }

        return $item;
    }

    /**
     * Проверка все ли транзакции валидны
     * @return bool
     */
    public function isAllTransactionsValid()
    {
        if($this->getTransaction()->isEmpty())
            return false;

        return $this->getTransaction()->every(function(VerifyTransferResponseType $item, $key) {
            return $item->isTransactionValid();
        });
    }

    /**
     * Связывание результатат проверки с запросом платежа
     * @param VerifyPaymentBatchRequest $request
     */
    public function relateRequestWithTransaction(VerifyPaymentBatchRequest $request)
    {
        $this->getTransaction()->each(function(VerifyTransferResponseType $item) use ($request) {
            $item->setTransactionRequest($request->getTransactionByIndex($item->getIndex()));
        });
    }
}