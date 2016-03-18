<?php

namespace Gousto\Acceptance\Tests;

use Dingo\Api\Http\Response;

class RatingsAcceptanceTest extends \TestCase
{
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
    public function testGetAllRatings()
    {
        $this->get('/api/v1/recipes/1/ratings')
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'created_at', 'updated_at', 'recipe_id', 'rating'
                    ]
                ], 'page', 'limit', 'total_pages', 'total_items'
            ])->assertResponseOk();
    }

    /**
     * @return void
     */
    public function testGetPaginatedRatings()
    {
        $this->get('/api/v1/recipes/1/ratings?page=1&limit=1')
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'created_at', 'updated_at', 'recipe_id', 'rating'
                    ]
                ], 'page', 'limit', 'total_pages', 'total_items'
            ])->seeJson([
                'page' => 1,
                'limit' => 1
            ])->assertResponseOk();
    }

    /**
     * @return void
     */
    public function testPostRating()
    {
        $this->json('POST', '/api/v1/recipes/1/ratings', [
            'recipe_id' => 1,
            'rating' => 5
        ])->seeJsonStructure([
            'id', 'created_at', 'updated_at', 'recipe_id', 'rating'
        ])->assertResponseStatus(Response::HTTP_CREATED);
    }

    /**
     * @return void
     */
    public function testPostRatingBadRequest()
    {
        $this->json('POST', '/api/v1/recipes/1/ratings', [
            'recipe_id' => 2,
            'rating' => 5
        ])->seeJsonStructure([
            'message', 'status_code'
        ])->assertResponseStatus(Response::HTTP_BAD_REQUEST);
    }
}
