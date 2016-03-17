<?php

namespace Gousto\Acceptance\Tests;

class RatingsAcceptanceTest extends \TestCase
{
    public function testGetAllRatings()
    {
        $this->get('/api/v1/recipes/1/ratings')
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'created_at', 'updated_at', 'recipe_id', 'rating'
                    ]
                ], 'page', 'limit', 'total_pages', 'total_items'
            ]);
    }
}
