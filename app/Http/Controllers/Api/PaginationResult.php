<?php


namespace App\Http\Controllers\Api;


use Illuminate\Contracts\Support\Arrayable;

class PaginationResult implements Arrayable
{
    protected $page;
    protected $limit;
    protected $total;
    protected $items = [];

    /**
     * PaginationResult constructor.
     * @param $page
     * @param $limit
     * @param $total
     */
    public function __construct($page, $limit, $total)
    {
        $this->page = $page;
        $this->limit = $limit;
        $this->total = $total;
    }

    /**
     * @param array $items
     * @return PaginationResult
     */
    public function setItems(array $items): PaginationResult
    {
        $this->items = $items;
        return $this;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $totalPages = ceil(($this->total / $this->limit));
        return [
            'page' => intval($this->page),
            'limit' => intval($this->limit),
            'total' => $totalPages > 0 ? $totalPages : 1,
            'items' => $this->items
        ];
    }
}