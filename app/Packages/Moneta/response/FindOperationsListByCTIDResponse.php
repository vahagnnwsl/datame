<?php

namespace App\Packages\Moneta\response;

use App\Packages\Moneta\types\OperationInfoList;

/**
 * Ответ на запрос FindOperationsListByCTIDRequest.
 * В результате возвращается список операций, разбитый по страницам.
 * Response to the request for searching transactions by a merchant transaction ID.
 * The response might include multiple transactions. If the list of transactions is long, the response is split into pages.
 *
 */
class FindOperationsListByCTIDResponse extends OperationInfoList
{

}