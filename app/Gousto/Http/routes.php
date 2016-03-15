<?php

/*
 * Gousto API routes.
*/

$api = app(Dingo\Api\Routing\Router::class);

$api->group(
    [
        'version' => 'v1',
        'prefix' => 'api/v1',
        'namespace' => 'Gousto\Http\Controllers',
    ],
    function ($api) {

        /**
         * Recipe routes
         */
        $api->get('recipes', 'RecipeController@index');
        $api->get('recipes/{id}', 'RecipeController@show');
        $api->post('recipes', 'RecipeController@store');
        $api->put('recipes/{id}', 'RecipeController@update');
        $api->patch('recipes/{id}', 'RecipeController@patch');

        /**
         * Rating routes
         */
        $api->get('recipes/{recipe_id}/ratings', 'RatingController@index');
        $api->post('recipes/{recipe_id}/ratings', 'RatingController@store');
    }
);
