<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 17/06/2017
 * Time: 12:28
 */

namespace App\Packages\Moneta\types;


/**
 * Тип, представляющий список операций. Содержит разбиение по страницам для отображения длинных списков.
 * A list of transactions. int transaction lists are split into multiple pages.
 *
 */
class OperationInfoList
{

    /**
     * Количество операций, возвращаемых в результате запроса.
     * The maximum number of transactions that the response must return on a single page.
     *
     *
     * @var int
     */
    public $pageSize = null;
    /**
     * Номер текущей страницы. Нумерация начинается с 1.
     * The current page number. Page numbering starts from 1.
     *
     *
     * @var int
     */
    public $pageNumber = null;
    /**
     * Максимальное количество страниц с операциями по данному запросу.
     * The total number of pages for a given request.
     *
     *
     * @var int
     */
    public $pagesCount = null;
    /**
     * Количество операций на текущей странице. Меньше или равно pageSize. Последняя страница может содержать операций меньше, чем pageSize.
     * The number of transactions on the current page. This number is less than or equal to the pageSize number.
     *
     *
     * @var int
     */
    public $size = null;
    /**
     * Общее количество операций, которое можно получить в данной выборке.
     * The total number of transactions for a given request.
     *
     *
     * @var int
     */
    public $totalSize = null;
    /**
     * Список операций.
     * The list of transactions.
     *
     *
     * @var OperationInfo
     */
    public $operation = null;
    /**
     * Список операций.
     * The list of transactions.
     *
     *
     * @param OperationInfo
     *
     * @return void
     */
    public function addOperation(OperationInfo $item)
    {
        $this->operation[] = $item;
    }

    public function parse($data)
    {
        $this->pageSize = $data->pageSize;
        $this->pageNumber = $data->pageNumber;
        $this->pagesCount = $data->pagesCount;
        $this->size = $data->size;
        $this->totalSize = $data->totalSize;

        if(isset($data->operation)) {
            $this->operation = new OperationInfo();
            $this->operation->id = $data->operation->id;
            foreach($data->operation->attribute as $attribute) {
                $this->operation->addAttribute((new KeyValueAttribute())->setKey($attribute->key)->setValue($attribute->value));
            }
        }

        return $this;
    }
}