<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 5/25/18
 * Time: 11:02 AM
 */

namespace App\Packages\Moneta\types;


/**
 * Тип, описывающий суммы и комиссии в предварительном расчете операции.
 * Transaction amount and estimated fees for a transaction.
 *
 */
class ForecastTransactionResponseType
{

    /**
     * Номер счета плательщика.
     * Account number of the payer.
     *
     *
     * @var integer
     */
    protected $payer = null;
    /**
     * Валюта счета плательщика.
     * Currency of the payer's account.
     *
     *
     * @var string
     */
    protected $payerCurrency = null;
    /**
     * Сумма к списанию.
     * The amount to be transferred from the payer's account.
     *
     *
     * @var float
     */
    protected $payerAmount = null;
    /**
     * Комиссия списания средств.
     * Transaction fee to the payer.
     *
     *
     * @var float
     */
    protected $payerFee = null;
    /**
     * Номер счета получателя.
     * Payee's account number.
     *
     *
     * @var integer
     */
    protected $payee = null;
    /**
     * Валюта счета получателя.
     * Currency of the payee's account.
     *
     *
     * @var string
     */
    protected $payeeCurrency = null;
    /**
     * Сумма к зачислению.
     * The amount to be transferred to the payee's account.
     *
     *
     * @var float
     */
    protected $payeeAmount = null;
    /**
     * Комиссия зачисления средств.
     * Transaction fee to the payee.
     *
     *
     * @var float
     */
    protected $payeeFee = null;
    /**
     * Название счета плательщика.
     * Payer's account alias.
     *
     *
     * @var string
     */
    protected $payerAlias = null;
    /**
     * Название счета получателя.
     * Payee's account alias.
     *
     *
     * @var string
     */
    protected $payeeAlias = null;

    /**
     * @return int
     */
    public function getPayer()
    {
        return $this->payer;
    }

    /**
     * @param int $payer
     * @return ForecastTransactionResponseType
     */
    public function setPayer($payer)
    {
        $this->payer = $payer;
        return $this;
    }

    /**
     * @return string
     */
    public function getPayerCurrency()
    {
        return $this->payerCurrency;
    }

    /**
     * @param string $payerCurrency
     * @return ForecastTransactionResponseType
     */
    public function setPayerCurrency($payerCurrency)
    {
        $this->payerCurrency = $payerCurrency;
        return $this;
    }

    /**
     * @return float
     */
    public function getPayerAmount()
    {
        return $this->payerAmount;
    }

    /**
     * @param float $payerAmount
     * @return ForecastTransactionResponseType
     */
    public function setPayerAmount($payerAmount)
    {
        $this->payerAmount = $payerAmount;
        return $this;
    }

    /**
     * @return float
     */
    public function getPayerFee()
    {
        return $this->payerFee;
    }

    /**
     * @param float $payerFee
     * @return ForecastTransactionResponseType
     */
    public function setPayerFee($payerFee)
    {
        $this->payerFee = $payerFee;
        return $this;
    }

    /**
     * @return int
     */
    public function getPayee()
    {
        return $this->payee;
    }

    /**
     * @param int $payee
     * @return ForecastTransactionResponseType
     */
    public function setPayee($payee)
    {
        $this->payee = $payee;
        return $this;
    }

    /**
     * @return string
     */
    public function getPayeeCurrency()
    {
        return $this->payeeCurrency;
    }

    /**
     * @param string $payeeCurrency
     * @return ForecastTransactionResponseType
     */
    public function setPayeeCurrency($payeeCurrency)
    {
        $this->payeeCurrency = $payeeCurrency;
        return $this;
    }

    /**
     * @return float
     */
    public function getPayeeAmount()
    {
        return $this->payeeAmount;
    }

    /**
     * @param float $payeeAmount
     * @return ForecastTransactionResponseType
     */
    public function setPayeeAmount($payeeAmount)
    {
        $this->payeeAmount = $payeeAmount;
        return $this;
    }

    /**
     * @return float
     */
    public function getPayeeFee()
    {
        return $this->payeeFee;
    }

    /**
     * @param float $payeeFee
     * @return ForecastTransactionResponseType
     */
    public function setPayeeFee($payeeFee)
    {
        $this->payeeFee = $payeeFee;
        return $this;
    }

    /**
     * @return string
     */
    public function getPayerAlias()
    {
        return $this->payerAlias;
    }

    /**
     * @param string $payerAlias
     * @return ForecastTransactionResponseType
     */
    public function setPayerAlias($payerAlias)
    {
        $this->payerAlias = $payerAlias;
        return $this;
    }

    /**
     * @return string
     */
    public function getPayeeAlias()
    {
        return $this->payeeAlias;
    }

    /**
     * @param string $payeeAlias
     * @return ForecastTransactionResponseType
     */
    public function setPayeeAlias($payeeAlias)
    {
        $this->payeeAlias = $payeeAlias;
        return $this;
    }


}