<?php

namespace Gousto\Http\Controllers;

use Dingo\Api\Http\Response;
use Gousto\Contracts\Transformable;
use Gousto\Services\RecipeService;
use Dingo\Api\Http\Request;

/**
 * The recipe resource.
 *
 * @Resource("Recipes", uri="api/v1/recipes")
 */
class RecipeController extends BaseController
{
    /**
     * @var RecipeService
     */
    protected $service;

    /**
     * @var Transformable
     */
    protected $transformer;

    /**
     * RecipeController constructor.
     *
     * @param RecipeService $service
     * @param Transformable $transformer
     */
    public function __construct(RecipeService $service, Transformable $transformer)
    {
        $this->service = $service;
        $this->transformer = $transformer;
    }

    /**
     * Show all recipes, optionally filtered by cuisine.
     *
     * @Get("/")
     * @Versions({"v1"})
     * @Parameters({
     *     @Parameter("recipe_cuisine", type="string", required=false, description="The name of cuisine to filter on"),
     *     @Parameter("page", type="integer", required=false, description="Page number for pagination"),
     *     @Parameter("limit", type="integer", required=false, description="Number of results per page")
     * })
     * @Response(200, body={"data":{"id":1,"created_at":"30\/06\/2015 17:58:00","updated_at":"30\/06\/2015 17:58:00","box_type":"vegetarian","title":"Sweet Chilli and Lime Beef on a Crunchy Fresh Noodle Salad","slug":"sweet-chilli-and-lime-beef-on-a-crunchy-fresh-noodle-salad","short_title":"","marketing_description":"Here we've used onglet steak which is an extra flavoursome cut of beef that should never be cooked past medium rare. So if you're a fan of well done steak, this one may not be for you. However, if you love rare steak and fancy trying a new cut, please be","calories_kcal":401,"protein_grams":12,"fat_grams":35,"carbs_grams":0,"bulletpoint1":"","bulletpoint2":"","bulletpoint3":"","recipe_diet_type_id":"meat","season":"all","base":"noodles","protein_source":"beef","preparation_time_minutes":35,"shelf_life_days":4,"equipment_needed":"Appeatites","origin_country":"Great Britain","recipe_cuisine":"asian","in_your_box":"","gousto_reference":59,"average_rating":2.5714285714286}, "page": null, "limit": null, "total_pages": null, "total_items": 1})
     *
     * @param Request $request
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        return $this->response()->array(
            $this->paginate(
                $this->service->getAllRecipes($request->except(self::$reserved)),
                ...array_values($request->only(self::$reserved))
            )
        );
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function show(Request $request, $id)
    {
        return $this->response()->array($this->service->getRecipeById($id));
    }

    /**
     * @param Request $request
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request)
    {
        return $this->response()->array(
            $this->service->createRecipe($request->json()->all())
        )->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->response()->array($this->service->updateRecipe($id, $request->json()->all()));
    }

    /**
     * @param Request $request
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function patch(Request $request, $id)
    {
        return $this->response()->array($this->service->updatePartialRecipe($id, $request->json()->all()));
    }
}
