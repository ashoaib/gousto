<?php

namespace Gousto\Http\Controllers;

use Gousto\Contracts\Transformable;
use Gousto\Services\RecipeService;
use Dingo\Api\Http\Request;
use Gousto\Transformers\RecipeTransformer;

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
        return $this->response()->array($this->paginate($this->service->getAllRecipes($request->except(self::$reserved)), ...array_values($request->only(self::$reserved))));
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
}
