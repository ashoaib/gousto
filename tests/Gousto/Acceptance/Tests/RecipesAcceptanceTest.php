<?php

namespace Gousto\Acceptance\Tests;

use Dingo\Api\Http\Response;

class RecipesAcceptanceTest extends \TestCase
{
    /**
     * @var array
     */
    private static $recipe_fields = [
        'id',
        'created_at',
        'updated_at',
        'box_type',
        'title',
        'slug',
        'short_title',
        'marketing_description',
        'calories_kcal',
        'protein_grams',
        'fat_grams',
        'carbs_grams',
        'bulletpoint1',
        'bulletpoint2',
        'bulletpoint3',
        'recipe_diet_type_id',
        'season',
        'base',
        'protein_source',
        'preparation_time_minutes',
        'shelf_life_days',
        'equipment_needed',
        'origin_country',
        'recipe_cuisine',
        'in_your_box',
        'gousto_reference',
        'average_rating',
    ];

    /**
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->baseUrl = env('BASE_URL');
    }

    /**
     * @return void
     */
    public function testGetAllRecipes()
    {
        $this->get('/api/v1/recipes')
            ->seeJsonStructure([
                'data' => [
                    '*' => self::$recipe_fields
                ], 'page', 'limit', 'total_pages', 'total_items'
            ])->assertResponseOk();
    }

    /**
     * @return void
     */
    public function testGetPaginatedRecipes()
    {
        $this->get('/api/v1/recipes?page=1&limit=1')
            ->seeJsonStructure([
                'data' => [
                    '*' => self::$recipe_fields
                ], 'page', 'limit', 'total_pages', 'total_items'
            ])->seeJson([
                'page' => 1,
                'limit' => 1
            ])->assertResponseOk();
    }

    /**
     * @return void
     */
    public function testGetRecipeById()
    {
        $this->get('/api/v1/recipes/1')
            ->seeJsonStructure(self::$recipe_fields)
            ->assertResponseOk();
    }

    /**
     * @return void
     */
    public function testGetRecipeByCriteria()
    {
        $this->get('/api/v1/recipes?recipe_cuisine=asian')
            ->seeJsonStructure([
                'data' => [
                    '*' => self::$recipe_fields
                ], 'page', 'limit', 'total_pages', 'total_items'
            ])->assertResponseOk();
    }

    /**
     * @return void
     */
    public function testCreateRecipe()
    {
        $this->json('POST', '/api/v1/recipes', [
            "box_type" => "vegetarian",
            "title" => "Sweet Chilli and Lime Beef on a Crunchy Fresh Noodle Salad",
            "slug" => "sweet-chilli-and-lime-beef-on-a-crunchy-fresh-noodle-salad",
            "short_title" => "",
            "marketing_description" => "Here we've used onglet steak which is an extra flavoursome cut of beef that should never be cooked past medium rare. So if you're a fan of well done steak, this one may not be for you. However, if you love rare steak and fancy trying a new cut, please be",
            "calories_kcal" => 401,
            "protein_grams" => 12,
            "fat_grams" => 2,
            "carbs_grams" => 0,
            "bulletpoint1" => "",
            "bulletpoint2" => "",
            "bulletpoint3" => "",
            "recipe_diet_type_id" => "meat",
            "season" => "all",
            "base" => "noodles",
            "protein_source" => "beef",
            "preparation_time_minutes" => 35,
            "shelf_life_days" => 4,
            "equipment_needed" => "Appetite",
            "origin_country" => "Great Britain",
            "recipe_cuisine" => "asian",
            "in_your_box" => "",
            "gousto_reference" => 59,
        ])->seeJsonStructure(self::$recipe_fields)
            ->assertResponseStatus(Response::HTTP_CREATED);
    }

    /**
     * @return void
     */
    public function testUpdateRecipe()
    {
        $this->json('PUT', '/api/v1/recipes/1', [
            "box_type" => "vegetarian",
            "title" => "Sweet Chilli and Lime Beef on a Crunchy Fresh Noodle Salad",
            "slug" => "sweet-chilli-and-lime-beef-on-a-crunchy-fresh-noodle-salad",
            "short_title" => "",
            "marketing_description" => "Here we've used onglet steak which is an extra flavoursome cut of beef that should never be cooked past medium rare. So if you're a fan of well done steak, this one may not be for you. However, if you love rare steak and fancy trying a new cut, please be",
            "calories_kcal" => 500,
            "protein_grams" => 12,
            "fat_grams" => 2,
            "carbs_grams" => 0,
            "bulletpoint1" => "",
            "bulletpoint2" => "",
            "bulletpoint3" => "",
            "recipe_diet_type_id" => "meat",
            "season" => "all",
            "base" => "noodles",
            "protein_source" => "beef",
            "preparation_time_minutes" => 35,
            "shelf_life_days" => 4,
            "equipment_needed" => "Appetite",
            "origin_country" => "Great Britain",
            "recipe_cuisine" => "asian",
            "in_your_box" => "",
            "gousto_reference" => 59,
        ])->seeJsonStructure(self::$recipe_fields)
            ->assertResponseOk();
    }

    /**
     * @return void
     */
    public function testPatchRecipe()
    {
        $this->json('PATCH', '/api/v1/recipes/1', [
            "box_type" => "gluten-free"
        ])->seeJsonStructure(self::$recipe_fields)
            ->assertResponseOk();
    }

    /**
     * @return void
     */
    public function testCreateRecipeBadRequest()
    {
        $this->json('POST', '/api/v1/recipes', [
            "box_type" => "vegetarian",
            "title" => "Sweet Chilli and Lime Beef on a Crunchy Fresh Noodle Salad",
            "slug" => "sweet-chilli-and-lime-beef-on-a-crunchy-fresh-noodle-salad",
            "short_title" => "",
            "marketing_description" => "Here we've used onglet steak which is an extra flavoursome cut of beef that should never be cooked past medium rare. So if you're a fan of well done steak, this one may not be for you. However, if you love rare steak and fancy trying a new cut, please be",
            "calories_kcal" => 401,
            "protein_grams" => 12,
            "fat_grams" => 2,
            "carbs_grams" => 0,
            "bulletpoint1" => "",
            "bulletpoint2" => "",
            "bulletpoint3" => "",
            "recipe_diet_type_id" => "meat",
            "season" => "all",
            "base" => "noodles",
            "protein_source" => "beef",
            "preparation_time_minutes" => 'bad-string',
            "shelf_life_days" => 4,
            "equipment_needed" => "Appetite",
            "origin_country" => "Great Britain",
            "recipe_cuisine" => "asian",
            "in_your_box" => "",
            "gousto_reference" => 59,
        ])->seeJsonStructure([
            'message', 'status_code'
        ])->assertResponseStatus(Response::HTTP_BAD_REQUEST);
    }

    /**
     * @return void
     */
    public function testUpdateNonExistingRecipe()
    {
        $this->json('PUT', '/api/v1/recipes/foo', [
            "box_type" => "vegetarian",
            "title" => "Sweet Chilli and Lime Beef on a Crunchy Fresh Noodle Salad",
            "slug" => "sweet-chilli-and-lime-beef-on-a-crunchy-fresh-noodle-salad",
            "short_title" => "",
            "marketing_description" => "Here we've used onglet steak which is an extra flavoursome cut of beef that should never be cooked past medium rare. So if you're a fan of well done steak, this one may not be for you. However, if you love rare steak and fancy trying a new cut, please be",
            "calories_kcal" => 500,
            "protein_grams" => 12,
            "fat_grams" => 2,
            "carbs_grams" => 0,
            "bulletpoint1" => "",
            "bulletpoint2" => "",
            "bulletpoint3" => "",
            "recipe_diet_type_id" => "meat",
            "season" => "all",
            "base" => "noodles",
            "protein_source" => "beef",
            "preparation_time_minutes" => 35,
            "shelf_life_days" => 4,
            "equipment_needed" => "Appetite",
            "origin_country" => "Great Britain",
            "recipe_cuisine" => "asian",
            "in_your_box" => "",
            "gousto_reference" => 59,
        ])->seeJsonStructure([
            'message', 'status_code'
        ])->assertResponseStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @return void
     */
    public function testGetNonExistingRecipeById()
    {
        $this->get('/api/v1/recipes/foo')
            ->seeJsonStructure([
                'message', 'status_code'
            ])->assertResponseStatus(Response::HTTP_NOT_FOUND);
    }
}
