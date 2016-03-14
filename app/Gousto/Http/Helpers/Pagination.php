<?php

namespace Gousto\Http\Helpers;

use Dingo\Api\Http\Request;
use Illuminate\Support\Collection;

/**
 * Class Pagination
 * @package Gousto\Http\Helpers
 */
trait Pagination
{
    /**
     * @var int
     */
    protected $page;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $total_pages;

    /**
     * @var array
     */
    protected static $reserved = [
        'page',
        'limit',
    ];

    /**
     * @param Collection $items
     * @param null $page
     * @param null $limit
     *
     * @return array
     */
    protected function paginate(Collection $items, $page = null, $limit = null)
    {
        $items = $items->all();

        if (!empty($page) && !empty($limit) && $page >= 0 && $limit >= 0) {
            $this->page = (int) $page;
            $this->limit = (int) $limit;
            $this->total_pages = ceil(count($items) / $this->limit);
            $items = array_slice($items, ($page - 1) * $limit, $limit);
        }

        return $this->respond($items);
    }

    /**
     * @param $items
     *
     * @return array
     */
    protected function respond($items)
    {
        return [
            'data' => $items,
            'page' => $this->page,
            'limit' => $this->limit,
            'total_pages' => $this->total_pages,
        ];
    }
}
