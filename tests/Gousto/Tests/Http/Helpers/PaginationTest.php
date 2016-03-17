<?php

namespace Gousto\Tests\Http\Helpers;

use Gousto\Http\Helpers\Pagination;
use Illuminate\Support\Collection;

class PaginationTest extends \TestCase
{
    /**
     * @var object
     */
    private $trait_object;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->trait_object = $this->createObjectForTrait();
    }

    /**
     * @param $items
     * @param $page
     * @param $limit
     * @param $expected
     *
     * @dataProvider providerTestCases
     */
    public function testPaginatedResponse($items, $page, $limit, $expected)
    {
        $this->assertEquals($expected, $this->trait_object->paginate($items, $page, $limit));
    }

    /**
     * @return array
     */
    public function providerTestCases()
    {
        $test_data = [
            [1],
            [2],
            [3],
            [4]
        ];

        return [
            [
                new Collection($test_data),
                null,
                null,
                [
                    'data' => $test_data,
                    'page' => null,
                    'limit' => null,
                    'total_pages' => null,
                    'total_items' => 4
                ]
            ],
            [
                new Collection($test_data),
                1,
                1,
                [
                    'data' => [$test_data[0]],
                    'page' => 1,
                    'limit' => 1,
                    'total_pages' => 4,
                    'total_items' => 4
                ]
            ],
            [
            new Collection($test_data),
                2,
                1,
                [
                    'data' => [$test_data[1]],
                    'page' => 2,
                    'limit' => 1,
                    'total_pages' => 4,
                    'total_items' => 4
                ]
            ],
            [
                new Collection($test_data),
                4,
                1,
                [
                    'data' => [$test_data[3]],
                    'page' => 4,
                    'limit' => 1,
                    'total_pages' => 4,
                    'total_items' => 4
                ]
            ],
            [
                new Collection($test_data),
                5,
                1,
                [
                    'data' => [],
                    'page' => 5,
                    'limit' => 1,
                    'total_pages' => 4,
                    'total_items' => 4
                ]
            ]
        ];
    }

    /**
     * @return object
     */
    private function createObjectForTrait()
    {
        return $this->getObjectForTrait(Pagination::class);
    }
}
