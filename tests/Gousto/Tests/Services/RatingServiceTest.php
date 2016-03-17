<?php

namespace Gousto\Tests\Services;

use Gousto\Contracts\Repository;
use Gousto\Repositories\RatingRepository;
use Gousto\Repositories\RecipeRepository;
use Gousto\Services\RatingService;
use Gousto\Services\RecipeService;
use Gousto\Validators\RatingValidator;
use Gousto\Contracts\Model;
use Gousto\Validators\RecipeValidator;

class RatingServiceTest extends \TestCase
{
    /**
     * @var RatingService
     */
    private $rating_service;

    /**
     * @return void
     */
    public function setUp()
    {
        $repository = $this->getMockRepository(RatingRepository::class);
        $validator = new RatingValidator();
        $service = $this->getMockRecipeService();
        $this->rating_service = new RatingService($repository, $validator, $service);
    }

    /**
     * @param array $ratings
     * @param float $expected_average
     *
     * @dataProvider providerTestCases
     */
    public function testAverageRatingCalculation($ratings, $expected_average)
    {
        $this->assertEquals(
            $expected_average,
            $this->invokeMethod(
                $this->rating_service,
                'calculateAverageRating',
                [$ratings]
            )
        );
    }

    /**
     * @return array
     */
    public function providerTestCases()
    {
        return [
            [
                [], 0
            ],
            [
                [['rating' => 1], ['rating' => 1]], 1
            ],
            [
                [['rating' => 3], ['rating' => 5], ['rating' => 2]], 10 / 3
            ],
        ];
    }

    /**
     * @param object $object
     * @param string $method_name
     * @param array $parameters
     *
     * @return mixed
     */
    public function invokeMethod(&$object, $method_name, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($method_name);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * @param string $class
     *
     * @return Repository
     */
    private function getMockRepository($class)
    {
        return $this->getMock(
            $class,
            [],
            [
                $this->getMockBuilder(Model::class)->getMock()
            ]
        );
    }

    /**
     * @return RecipeService
     */
    private function getMockRecipeService()
    {
        return $this->getMock(
            RecipeService::class,
            [],
            [
                $this->getMockRepository(RecipeRepository::class),
                new RecipeValidator()
            ]
        );
    }
}
