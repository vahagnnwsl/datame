<?php
/**
 * Created by PhpStorm.
 * User: won
 * Date: 17/06/2017
 * Time: 12:27
 */

namespace App\Packages\Moneta\types;


/**
 * Тип, позволяющий задать необходимую страницу при отображении длинных списков.
 * Specifies paginating parameters for a long list of MONETA.RU objects, such as transactions or users.
 *
 */
class Pager
{

    /**
     * Номер страницы, которую нужно получить.
     * Минимальное значение равно 1.
     * Значение по умолчанию равно 1.
     * Specifies the number of the page that you want to get.
     * Minimum value: 1.
     * Default value: 1.
     *
     *
     * @var int
     */
    protected $pageNumber = null;
    /**
     * Сколько записей необходимо получить на одной странице.
     * Минимальное значение равно 1.
     * Максимальное значение равно 1000.
     * Значение по умолчанию равно 25.
     * Specifies the maximum number of transactions per page.
     * Valid range: 1-1000.
     * Default value: 25.
     *
     *
     * @var int
     */
    protected $pageSize = null;

    /**
     * @return int
     */
    public function getPageNumber()
    {
        return $this->pageNumber;
    }

    /**
     * @param int $pageNumber
     * @return $this
     */
    public function setPageNumber($pageNumber)
    {
        $this->pageNumber = $pageNumber;
        return $this;
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @param int $pageSize
     * @return $this
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
        return $this;
    }


}