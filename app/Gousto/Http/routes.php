<?php

/*
 * Gousto API routes.
*/

$api = app(Dingo\Api\Routing\Router::class);

$api->group(
    [
        'version' => 'v1',
        'prefix' => 'api/v1',
    ],
    function ($api) {
        $api->get('recipes', 'Gousto\Http\Controllers\RecipeController@index');
        $api->get('recipes/{id}', 'Gousto\Http\Controllers\RecipeController@show');
    }
);
