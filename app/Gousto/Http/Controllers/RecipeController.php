<?php

namespace Gousto\Http\Controllers;

use Dingo\Api\Http\Response;
use Gousto\Contracts\Transformable;
use Gousto\Services\RecipeService;
use Dingo\Api\Http\Request;

/**
 * Class RecipeController
 * @package Gousto\Http\Controllers
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
