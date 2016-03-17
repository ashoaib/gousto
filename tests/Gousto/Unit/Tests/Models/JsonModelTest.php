<?php

namespace Gousto\Unit\Tests\Models;

use Gousto\Models\JsonModel;
use Illuminate\Support\Collection;

class JsonModelTest extends \TestCase
{
    /**
     * @var JsonModel
     */
    private $model;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->model = $this->initialiseModel();
    }

    /**
     * @return void
     */
    public function testGettingTableData()
    {
        $this->assertEquals(
            [
                'data' => [
                    '1' => ['recipe_cuisine' => 'asian'],
                    '2' => ['recipe_cuisine' => 'mexican'],
                ],
                'indexes' => [
                    'recipe_cuisine' => [
                        'asian' => [1],
                        'mexican' => [2]
                    ]
                ],
                'increment' => 2
            ],
            $this->model->getTableData()
        );
    }

    /**
     * @return void
     */
    public function testAll()
    {
        $object_1 = new \stdClass();
        $object_1->recipe_cuisine = 'asian';
        $object_2 = new \stdClass();
        $object_2->recipe_cuisine = 'mexican';

        $this->assertEquals(
            new Collection([
                $object_1,
                $object_2
            ]),
            $this->model->all()
        );
    }

    /**
     * @return void
     */
    public function testFindByCriteria()
    {
        $object = new \stdClass();
        $object->recipe_cuisine = 'asian';

        $this->assertEquals(
            new Collection([
                $object
            ]),
            $this->model->findBy(['recipe_cuisine' => 'asian'])
        );
    }

    /**
     * @return void
     */
    public function testFindById()
    {
        $this->assertEquals(
            ['recipe_cuisine' => 'mexican'],
            $this->model->find(2)
        );
    }

    /**
     * @return JsonModel
     */
    public function initialiseModel()
    {
        /**
         * @var JsonModel $model
         */
        $model = $this->getMockBuilder(JsonModel::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        $model->setTable('mock');
        $model->loadData($this->getTestData());
        return $model;
    }

    /**
     * @return string
     */
    private function getTestData()
    {
        return '{"mock":{"data":{"1":{"recipe_cuisine":"asian"},"2":{"recipe_cuisine":"mexican"}},"indexes":{"recipe_cuisine":{"asian":[1],"mexican":[2]}},"increment":2}}';
    }
}
