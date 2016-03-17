<?php

namespace Gousto\Unit\Tests\Validators;

use Gousto\Contracts\Validatable;
use Gousto\Validators\RecipeValidator;

class RecipeValidatorTest extends \TestCase
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
        $this->validator = new RecipeValidator();
    }

    /**
     * @param array $test_data
     *
     * @dataProvider providerPassingTestCases
     */
    public function testRatingValidatorPasses($test_data)
    {
        $this->assertTrue($this->validator->validate($test_data));
    }

    /**
     * @param array $test_data
     *
     * @dataProvider providerFailingTestCases
     */
    public function testRatingValidatorFailures($test_data)
    {
        $this->assertFalse($this->validator->validate($test_data));
    }

    /**
     * @return array
     */
    public function providerPassingTestCases()
    {
        return [
            [[
                'box_type' => 'string',
                'title' => 'string',
                'slug' => 'string',
                'short_title' => 'string',
                'marketing_description' => 'string',
                'calories_kcal' => 123,
                'protein_grams' => 456,
                'fat_grams' => 789,
                'carbs_grams' => '12',
                'bulletpoint1' => 'string',
                'bulletpoint2' => '',
                'bulletpoint3' => '',
                'recipe_diet_type_id' => 'string',
                'season' => 'string',
                'base' => 'string',
                'protein_source' => 'string',
                'preparation_time_minutes' => 12.4,
                'shelf_life_days' => 7,
                'equipment_needed' => 'string',
                'origin_country' => 'string',
                'recipe_cuisine' => 'string',
                'in_your_box' => 'string',
                'gousto_reference' => 1,
                'average_rating' => 4.5,
            ]],
            [[
                'box_type' => 'string',
                'title' => 'string',
                'slug' => 'string',
                'short_title' => 'string',
                'marketing_description' => 'string',
                'protein_grams' => 456,
                'carbs_grams' => '12',
                'bulletpoint1' => 'string',
                'season' => 'string',
                'base' => 'string',
                'preparation_time_minutes' => 12.4,
                'shelf_life_days' => 7,
                'origin_country' => 'string',
                'gousto_reference' => 1,
                'average_rating' => 4.5,
            ]]
        ];
    }

    /**
     * @return array
     */
    public function providerFailingTestCases()
    {
        return [
            [[
                'box_type' => 'string',
                'title' => 'string',
                'slug' => 'string',
                'short_title' => 'string',
                'marketing_description' => 'string',
                'calories_kcal' => 'foo',
                'protein_grams' => 456,
                'fat_grams' => 'bar',
                'carbs_grams' => '12',
                'bulletpoint1' => 'string',
                'bulletpoint2' => '',
                'bulletpoint3' => '',
                'recipe_diet_type_id' => 'string',
                'season' => 'string',
                'base' => 'string',
                'protein_source' => 'string',
                'preparation_time_minutes' => 12.4,
                'shelf_life_days' => 7,
                'equipment_needed' => 'string',
                'origin_country' => 'string',
                'recipe_cuisine' => 'string',
                'in_your_box' => 'string',
                'gousto_reference' => 1,
                'average_rating' => 4.5,
            ]],
            [[
                'box_type' => 'string',
                'title' => 'string',
                'slug' => 'string',
                'short_title' => 'string',
                'marketing_description' => 'string',
                'protein_grams' => 456,
                'carbs_grams' => '12',
                'bulletpoint1' => 'string',
                'season' => 'string',
                'base' => 'string',
                'preparation_time_minutes' => 12.4,
                'shelf_life_days' => 7,
                'origin_country' => 'string',
                'gousto_reference' => 1.2,
                'average_rating' => 'test',
            ]]
        ];
    }
}
