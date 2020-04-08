<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 5/24/18
 * Time: 7:11 PM
 */

namespace App\Packages\Moneta\types;

/**
 * Тип, описывающий атрибуты операции в ответах в пакетном запросе.
 * Transaction attributes for responses in batch mode.
 *
 */
class OperationInfoBatchResponseType
{

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
     *
     *
     * @var MonetaOperationInfo
     */
    public $transaction = null;

    /**
     * Поле содержит код ошибки. Поле заполнено только тогда, когда есть описание ошибки в поле error.
     * Для получения этого поля в запросе необходимо выставлять атрибут version равный или больше VERSION_2.
     * Коды ошибок совпадают со значением элемента faultDetail (смотрите описание для этого элемента).
     * If an error occurs when processing a transaction, the errorCode element contains an error code.
     * MONETA.RU returns this element only if you set the version attribute of your request to VERSION_2.
     * See the faultDetail element for the error code descriptions.
     *
     *
     * @var string
     */
    public $errorCode = null;

}