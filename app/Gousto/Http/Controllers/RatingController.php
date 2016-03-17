<?php

namespace Gousto\Http\Controllers;

use Dingo\Api\Http\Response;
use Gousto\Services\RatingService;
use Dingo\Api\Http\Request;

/**
 * Class RatingController
 * @package Gousto\Http\Controllers
 */
class RatingController extends BaseController
{
    /**
     * @var RatingService
     */
    protected $service;

    /**
     * RatingController constructor.
     *
     * @param RatingService $service
     */
    public function __construct(RatingService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request, $recipe_id)
    {
        return $this->response()->array(
            $this->paginate(
                $this->service->getAllRatings($recipe_id),
                ...array_values($request->only(self::$reserved))
            )
        );
    }

    /**
     * @param Request $request
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store(Request $request, $recipe_id)
    {
        return $this->response()->array(
            $this->service->addRating($recipe_id, $request->json()->all())
        )->setStatusCode(Response::HTTP_CREATED);
    }
}
