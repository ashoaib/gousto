<?php

namespace Gousto\Tests\Validators;

use Gousto\Contracts\Validatable;
use Gousto\Validators\RatingValidator;

class RatingValidatorTest extends \TestCase
{
    /**
     * @var Validatable
     */
    private $validator;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->validator = new RatingValidator();
    }

    /**
     * @param mixed $recipe_id
     * @param mixed $rating
     *
     * @dataProvider providerPassingTestCases
     */
    public function testRatingValidatorPasses($recipe_id, $rating)
    {
        $this->assertTrue($this->validator->validate([
            'recipe_id' => $recipe_id,
            'rating' => $rating
        ]));
    }

    /**
     * @param mixed $recipe_id
     * @param mixed $rating
     *
     * @dataProvider providerFailingTestCases
     */
    public function testRatingValidatorFailures($recipe_id, $rating)
    {
        $this->assertFalse($this->validator->validate([
            'recipe_id' => $recipe_id,
            'rating' => $rating
        ]));
    }

    /**
     * @return array
     */
    public function providerPassingTestCases()
    {
        return [
            [1, 2],
            [1, 1],
            [1, 5],
        ];
    }

    /**
     * @return array
     */
    public function providerFailingTestCases()
    {
        return [
            ['foo', 2],
            [1, 'bar'],
            ['foo', 'bar'],
            [null, null],
            [1, 0],
            [1, 6],
        ];
    }
}
