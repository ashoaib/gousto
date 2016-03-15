<?php

namespace Gousto\Services;

use Gousto\Contracts\Repository;
use Gousto\Contracts\Validatable;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class RatingService
 * @package Gousto\Services
 */
class RatingService
{
    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var Validatable
     */
    protected $validator;

    /**
     * @var RecipeService
     */
    protected $recipe_service;

    /**
     * RecipeService constructor.
     *
     * @param Repository $repository
     * @param Validatable $validator
     */
    public function __construct(Repository $repository, Validatable $validator, RecipeService $recipe_service)
    {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->recipe_service = $recipe_service;
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllRatings($recipe_id)
    {
        return $this->repository->findBy(['recipe_id' => $recipe_id]);
    }

    /**
     * @param $recipe_id
     * @param array $data
     *
     * @return array
     */
    public function addRating($recipe_id, array $data)
    {
        // Extra validation hack for now.
        $this->validator->addRule('recipe_id', "required|numeric|min:{$recipe_id}|max:{$recipe_id}");

        if ($this->validator->validate($data)) {
            $result = $this->repository->create($this->validator->getNulledData());
            $this->updateAverageRating($recipe_id, $data);
            return $result;
        } else {
            throw new BadRequestHttpException($this->validator->errors());
        }
    }

    /**
     * @param $recipe_id
     * @param array $data
     */
    protected function updateAverageRating($recipe_id, array $data)
    {
        $average_rating = 0;
        $ratings = array_merge($this->getAllRatings($recipe_id)->all(), [$data]);

        foreach ($ratings as $rating) {
            $rating = (array) $rating;
            $average_rating += $rating['rating'];
        }

        $this->recipe_service->updatePartialRecipe($recipe_id, [
            'average_rating' => ($average_rating / count($ratings))
        ]);
    }
}
