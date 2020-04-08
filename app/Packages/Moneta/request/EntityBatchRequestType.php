<?php

namespace App\Packages\Moneta\request;

use App\Packages\Moneta\types\Entity;

/**
 * Тип, описывающий параметры в запросах в пакетном режиме.
 * Parameters for requests in batch mode.
 *
 */
class EntityBatchRequestType extends Entity
{

    /**
     * Флаг, указывающий выполнять ли все денежные переводы в одной транзакции. Если transactional = true, то:
     * При возникновении ошибки все проведенные операции будут отменены.
     * Можно проводить только операции со счетами монеты. Нельзя выводить деньги на внешние платежные системы.
     * Если transactional = false, то:
     * При возникновении ошибки все проведенные операции будут сохранены.
     * Можно проводить любые операции, которые доступны в TransferRequest.
     * Если выставить флаг exitOnFailure = false, то при возникновении ошибки можно пропустить операцию и продолжить выполнение операций дальше.
     * Indicates whether to merge all transfers during batch processing into a single transaction. Valid values are:
     * True. If an error occurs, all of the transactions are canceled. Only
     * transactions with MONETA.RU accounts are supported. Withdrawals to third-party
     * payment systems are not allowed.
     * False. If an error occurs, MONETA.RU cancels only the current
     * transaction. Processed transactions are not rolled back. Any transactions that
     * are supported by the Transfer request are allowed. If you set the exitOnFailure
     * element to false, the Merchant API skips the current transaction and continues
     * processing other transactions.
     *
     *
     * @var boolean
     */
    public $transactional = false;

    /**
     * Флаг, указывающий прерывать ли выполнение пакета операций, если произошла ошибка.
     * Используется только при transactional = false.
     * If the transactional element is set to false, indicates whether to stop batch processing
     * if an error occurs in one of the transactions.
     *
     *
     * @var boolean
     */
    public $exitOnFailure = false;
}