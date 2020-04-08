<?php

namespace App\Packages\Moneta\types;

use Illuminate\Support\Collection;

/**
 * Тип, описывающий операцию в системе МОНЕТА.РУ. Он представляет собой номер (id) операции и
 * набор полей, которые присутствуют у данной операции. Поля представлены в виде "ключ-значение".
 * Provides information about a MONETA.RU transaction. The information includes a transaction ID and transaction attributes in the form of key-value pairs.
 *
 */
class OperationInfo
{

    /**
     * Номер операции.
     * Transaction ID.
     *
     *
     * @var int
     */
    public $id = null;
    /**
     * Поля операции. Полей в операции может быть 0 и более. Список полей:
     * clienttransaction. Внешний (не в системе МОНЕТА.РУ) номер операции.
     * statusid. Статус операции.
     * typeid. Тип операции:
     * 2,10. Оплата с пользовательского счета.
     * 3,19. Оплата с внешней платежной системы.
     * 7,14. Пополнение счета.
     * 4,13. Вывод на внешнюю платежную систему.
     * 11. Вывод на пользовательский счет.
     * 12. Внутренний перевод.
     * 17,18. Возврат на внешнюю платежную систему.
     * category. Категория операции:
     * DEPOSIT - ввод средств
     * WITHDRAWAL - вывод средств
     * TRANSFER - внутренний перевод
     * BUSINESS - товары и услуги
     * modified. Время последнего изменения операции.
     * sourceaccountid. Номер счета, с которого произведена операция.
     * sourcecurrencycode. Валюта счета.
     * sourceamount. Сумма по операции.
     * sourceamountfee. Сумма комиссии.
     * sourceamounttotal. Общая сумма с учетом комиссии.
     * targetaccountid. Корреспондентский счет.
     * targetalias. Название корреспондентского счета.
     * isreversed.
     * true. sourceaccountid=получатель, targetaccountid=плательщик.
     * false. sourceaccountid=плательщик, targetaccountid=получатель.
     * customfield: custom_attribute_name
     * . Произвольный набор значений. В операции их может быть несколько.
     * Полный ключ атрибута состоит из префикса ("customfield:") и тэга (32 символа). Например, "customfield:name".
     * Transaction attributes. Valid attributes are:
     * clienttransaction. Merchant transaction ID.
     * statusid. Transaction status.
     * typeid. Transaction type ID. Valid transaction type IDs are:
     * 2,10. Payment from a MONETA.RU account.
     * 3,19. Payment from an external payment system.
     * 7,14. Deposit.
     * 4,13. Withdrawal to an external payment system.
     * 11. Withdrawal to another MONETA.RU account.
     * 12. Transfer to a MONETA.RU account that belongs to you or to another user.
     * 17,18. Refund to an external payment system.
     * category. Transaction category. Valid values are:
     * DEPOSIT
     * WITHDRAWAL
     * TRANSFER
     * BUSINESS
     * modified. Transaction modification date and time.
     * sourceaccountid. Account number.
     * sourcecurrencycode. Account currency.
     * sourceamount. Transaction amount.
     * sourceamountfee. Transaction fee.
     * sourceamounttotal. Total transaction amount including the transaction fee.
     * targetaccountid. Correspondent account number.
     * targetalias. Alias of the correspondent account.
     * isreversed. Indicates whether sourceaccountid and targetaccountid are associated with the payer and payee or vice versa.
     * true. sourceaccountid=payee and targetaccountid=payer.
     * false. sourceaccountid=payer and targetaccountid=payee.
     * customfield: custom_attribute_name
     * . Custom attribute. Transaction information might include multiple custom attributes.
     * The custom_attribute_name part of the custom attribute key might include up to 32 characters.
     * subscriberid. A unique identifier of a customer in the merchant system.
     *
     *
     * @var Collection
     */
    public $attribute = null;

    public function __construct()
    {
        $this->attribute = new Collection();
    }

    /**
     * Поля операции. Полей в операции может быть 0 и более. Список полей:
     * clienttransaction. Внешний (не в системе МОНЕТА.РУ) номер операции.
     * statusid. Статус операции.
     * typeid. Тип операции:
     * 2,10. Оплата с пользовательского счета.
     * 3,19. Оплата с внешней платежной системы.
     * 7,14. Пополнение счета.
     * 4,13. Вывод на внешнюю платежную систему.
     * 11. Вывод на пользовательский счет.
     * 12. Внутренний перевод.
     * 17,18. Возврат на внешнюю платежную систему.
     * category. Категория операции:
     * DEPOSIT - ввод средств
     * WITHDRAWAL - вывод средств
     * TRANSFER - внутренний перевод
     * BUSINESS - товары и услуги
     * modified. Время последнего изменения операции.
     * sourceaccountid. Номер счета, с которого произведена операция.
     * sourcecurrencycode. Валюта счета.
     * sourceamount. Сумма по операции.
     * sourceamountfee. Сумма комиссии.
     * sourceamounttotal. Общая сумма с учетом комиссии.
     * targetaccountid. Корреспондентский счет.
     * targetalias. Название корреспондентского счета.
     * isreversed.
     * true. sourceaccountid=получатель, targetaccountid=плательщик.
     * false. sourceaccountid=плательщик, targetaccountid=получатель.
     * customfield: custom_attribute_name
     * . Произвольный набор значений. В операции их может быть несколько.
     * Полный ключ атрибута состоит из префикса ("customfield:") и тэга (32 символа). Например, "customfield:name".
     * Transaction attributes. Valid attributes are:
     * clienttransaction. Merchant transaction ID.
     * statusid. Transaction status.
     * typeid. Transaction type ID. Valid transaction type IDs are:
     * 2,10. Payment from a MONETA.RU account.
     * 3,19. Payment from an external payment system.
     * 7,14. Deposit.
     * 4,13. Withdrawal to an external payment system.
     * 11. Withdrawal to another MONETA.RU account.
     * 12. Transfer to a MONETA.RU account that belongs to you or to another user.
     * 17,18. Refund to an external payment system.
     * category. Transaction category. Valid values are:
     * DEPOSIT
     * WITHDRAWAL
     * TRANSFER
     * BUSINESS
     * modified. Transaction modification date and time.
     * sourceaccountid. Account number.
     * sourcecurrencycode. Account currency.
     * sourceamount. Transaction amount.
     * sourceamountfee. Transaction fee.
     * sourceamounttotal. Total transaction amount including the transaction fee.
     * targetaccountid. Correspondent account number.
     * targetalias. Alias of the correspondent account.
     * isreversed. Indicates whether sourceaccountid and targetaccountid are associated with the payer and payee or vice versa.
     * true. sourceaccountid=payee and targetaccountid=payer.
     * false. sourceaccountid=payer and targetaccountid=payee.
     * customfield: custom_attribute_name
     * . Custom attribute. Transaction information might include multiple custom attributes.
     * The custom_attribute_name part of the custom attribute key might include up to 32 characters.
     * subscriberid. A unique identifier of a customer in the merchant system.
     *
     *
     * @param KeyValueAttribute
     *
     * @return void
     */
    public function addAttribute(KeyValueAttribute $item)
    {
        $this->attribute->push($item);
    }
}