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
    protected $page = null;

    /**
     * @var int
     */
    protected $limit = null;

    /**
     * @var int
     */
    protected $total_pages = null;

    /**
     * @var int
     */
    protected $total_items = null;

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
    public function paginate(Collection $items, $page = null, $limit = null)
    {
        $items = $items->all();
        $this->total_items = count($items);

        if (!empty($page) && !empty($limit) && $page >= 0 && $limit >= 0) {
            $this->page = (int) $page;
            $this->limit = (int) $limit;
            $this->total_pages = (int) ceil($this->total_items / $this->limit);
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
            'total_items' => $this->total_items
        ];
    }
}
