<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 17/06/2017
 * Time: 12:24
 */

namespace App\Packages\Moneta\request;

use App\Packages\Moneta\types\Pager;


/**
 * Запрос на получение данных по внешнему номеру операции (номеру не в системе МОНЕТА.РУ).
 * По внешнему номеру операции может быть найдено несколько операций в системе МОНЕТА.РУ. Поэтому операции возвращаются списком,
 * разбитым на страницы.
 * Размером страницы можно управлять через поле pager. Если данные не найдены, то size в ответе равен 0.
 * Request for retrieving transaction details by a merchant transaction ID.
 * The response might include multiple transactions. A long list of transactions might be split into multiple pages.
 * If MONETA.RU finds no transactions, the size element in the response is set to 0.
 *
 */
class FindOperationsListByCTIDRequest
{

    /**
     * Настройки страницы данных.
     * Specifies the sequence number of the page that you want to retrieve if the list of transactions includes multiple pages.
     *
     *
     * @var Pager
     */
    public $pager = null;

    /**
     * Номер счета в системе МОНЕТА.РУ.
     * MONETA.RU account number.
     *
     *
     * @var int
     */
    public $accountId = null;

    /**
     * Внешний номер операции.
     * The unique identifier of the transaction in the merchant system.
     *
     *
     * @var string
     */
    public $clientTransaction = null;

}