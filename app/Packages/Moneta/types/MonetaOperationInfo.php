<?php
/**
 * Created by PhpStorm.
 * User: lodar
 * Date: 12.07.16
 * Time: 23:17
 */

namespace App\Packages\Moneta\types;


use App\Packages\Moneta\TraitClientTransaction;
use Illuminate\Support\Collection;

class MonetaOperationInfo
{
    use TraitClientTransaction;

    protected $index;
    protected $transactionRequest;

    /**
     * Номер операции
     * Transaction ID
     *
     *
     * @var integer
     */
    public $id = null;

    /**
     * Поле содержит описание ошибки, если операция не была проведена. В этом случае поле transaction - пустое.
     * Если операция проведена, то поле error пустое, а поле transaction содержит детали операции.
     * If an error occurs when processing a transaction, the error element contains an error description.
     * If the transaction completes successfully, the error element is empty and the transaction element contains transaction details.
     *
     *
     * @var string
     */
    public $error = null;

    /**
     * Поля операции. Полей в операции может быть 0 и более.
     * Список полей:
     * clienttransaction - внешний (не в системе МОНЕТА.РУ) номер операции;
     * statusid - статус операции;
     * typeid - тип операции:
     * 2,10 - оплата с пользовательского счета,
     * 3 - оплата с внешней платежной системы,
     * 7,14 - пополнение счета,
     * 4,13 - вывод на внешнюю платежную систему,
     * 11 - вывод на пользовательский счет,
     * 12 - внутренний перевод;
     * category - категория операции:
     * DEPOSIT - ввод средств,
     * WITHDRAWAL - вывод средств,
     * TRANSFER - внутренний перевод,
     * BUSINESS - товары и услуги;
     * modified - время последнего изменения операции;
     * sourceaccountid - номер счета, с которого произведена операция;
     * sourcecurrencycode - валюта счета;
     * sourceamount - сумма по операции;
     * sourceamountfee - сумма комиссии;
     * sourceamounttotal - общая сумма с учетом комиссии;
     * targetaccountid - корреспондентский счет;
     * targetalias - название корреспондентского счета;
     * isreversed - если "true", то sourceaccountid=получатель, targetaccountid=плательщик;
     * customfield:* - произвольный набор значений.
     * В операции их может быть несколько.
     * Полный ключ атрибута состоит из префикса ("customfield:") и тэга (32 символа).
     * Например, "customfield:name".
     * Transaction attributes. Attributes set may be empty.
     * Attributes list:
     * clienttransaction - external transaction ID;
     * statusid - transaction status;
     * typeid - transaction type:
     * 2,10 - payment from user account,
     * 3 - payment from external system,
     * 7,14 - deposit,
     * 4,13 - withdrawal to external system,
     * 11 - withdrawal to user account,
     * 12 - inner transfer;
     * category - transaction category:
     * DEPOSIT,
     * WITHDRAWAL,
     * TRANSFER,
     * BUSINESS;
     * modified - transaction modification timestamp;
     * sourceaccountid - account number;
     * sourcecurrencycode - account currency;
     * sourceamount - transaction amount;
     * sourceamountfee - transaction commission;
     * sourceamounttotal - total transaction amount incl. commission;
     * targetaccountid - correspondent account number;
     * targetalias - alias of correspondent account;
     * isreversed - if "true", then sourceaccountid=payee, targetaccountid=payer;
     * customfield:* - custom list of values.
     * The transaction may contain several attributes with different keys.
     * The full attribute's key consists of prefix ("customfield:") and tag (32 characters).
     * For example, "customfield:name".
     *
     *
     * @var MonetaKeyValueAttribute[]
     */
    public $attribute = null;

    public function __construct()
    {
        $this->attribute = [];
    }

    /**
     * Поля операции. Полей в операции может быть 0 и более.
     * Список полей:
     * clienttransaction - внешний (не в системе МОНЕТА.РУ) номер операции;
     * statusid - статус операции;
     * typeid - тип операции:
     * 2,10 - оплата с пользовательского счета,
     * 3 - оплата с внешней платежной системы,
     * 7,14 - пополнение счета,
     * 4,13 - вывод на внешнюю платежную систему,
     * 11 - вывод на пользовательский счет,
     * 12 - внутренний перевод;
     * category - категория операции:
     * DEPOSIT - ввод средств,
     * WITHDRAWAL - вывод средств,
     * TRANSFER - внутренний перевод,
     * BUSINESS - товары и услуги;
     * modified - время последнего изменения операции;
     * sourceaccountid - номер счета, с которого произведена операция;
     * sourcecurrencycode - валюта счета;
     * sourceamount - сумма по операции;
     * sourceamountfee - сумма комиссии;
     * sourceamounttotal - общая сумма с учетом комиссии;
     * targetaccountid - корреспондентский счет;
     * targetalias - название корреспондентского счета;
     * isreversed - если "true", то sourceaccountid=получатель, targetaccountid=плательщик;
     * customfield:* - произвольный набор значений.
     * В операции их может быть несколько.
     * Полный ключ атрибута состоит из префикса ("customfield:") и тэга (32 символа).
     * Например, "customfield:name".
     * Transaction attributes. Attributes set may be empty.
     * Attributes list:
     * clienttransaction - external transaction ID;
     * statusid - transaction status;
     * typeid - transaction type:
     * 2,10 - payment from user account,
     * 3 - payment from external system,
     * 7,14 - deposit,
     * 4,13 - withdrawal to external system,
     * 11 - withdrawal to user account,
     * 12 - inner transfer;
     * category - transaction category:
     * DEPOSIT,
     * WITHDRAWAL,
     * TRANSFER,
     * BUSINESS;
     * modified - transaction modification timestamp;
     * sourceaccountid - account number;
     * sourcecurrencycode - account currency;
     * sourceamount - transaction amount;
     * sourceamountfee - transaction commission;
     * sourceamounttotal - total transaction amount incl. commission;
     * targetaccountid - correspondent account number;
     * targetalias - alias of correspondent account;
     * isreversed - if "true", then sourceaccountid=payee, targetaccountid=payer;
     * customfield:* - custom list of values.
     * The transaction may contain several attributes with different keys.
     * The full attribute's key consists of prefix ("customfield:") and tag (32 characters).
     * For example, "customfield:name".
     *
     *
     * @param MonetaKeyValueAttribute
     *
     * @return void
     */
    public function addAttribute(MonetaKeyValueAttribute $item)
    {
        $this->attribute[] = $item;
    }

    /**
     * Возвращает статус платежа
     * @return bool
     */
    public function isSucceed()
    {
        return is_null((new Collection($this->attribute))->first(function(MonetaKeyValueAttribute $item) {
            return $item->getKey() == 'statusid' && $item->getValue() == 'SUCCEED';
        })) ? false : true;
    }

    /**
     * Возвращает айди транзакции
     * @return MonetaKeyValueAttribute|null
     */
    public function getClientTransaction() {
        return (new Collection($this->attribute))->first(function(MonetaKeyValueAttribute $attr) {
           return $attr->getKey() == "clienttransaction";
        });
    }

    /**
     * Возвращает билл ид транзакции
     * @return mixed|null
     */
    public function getBillId() {

        return is_null($this->getClientTransaction()) ? null : $this->getTransactionBillId($this->getClientTransaction()->getValue());
    }

    /**
     * Возвращает парт ид транзакции
     * @return mixed|null
     */
    public function getPartId() {
        return is_null($this->getClientTransaction()) ? null : $this->getTransactionPartId($this->getClientTransaction()->getValue());
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return MonetaOperationInfo
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $error
     * @return MonetaOperationInfo
     */
    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param mixed $index
     * @return MonetaOperationInfo
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
     * @return MonetaOperationInfo
     */
    public function setTransactionRequest(PaymentTransaction $transactionRequest)
    {
        $this->transactionRequest = $transactionRequest;
        return $this;
    }



}