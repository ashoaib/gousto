<?php

namespace Gousto\Services;

use Gousto\Contracts\Repository;
use Gousto\Contracts\Validatable;
use Gousto\Exceptions\RecipeNotFoundException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class RecipeService
 * @package Gousto\Services
 */
class RecipeService
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
     * RecipeService constructor.
     *
     * @param Repository $repository
     */
    public function __construct(Repository $repository, Validatable $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * @param array $criteria
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllRecipes(array $criteria = [])
    {
        if (empty($criteria)) {
            return $this->repository->all();
        } else {
            if ($this->validator->validate($criteria)) {
                return $this->repository->findBy($criteria);
            } else {
                throw new BadRequestHttpException($this->validator->errors());
            }
        }
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function getRecipeById($id)
    {
        try {
            return $this->repository->find($id);
        } catch (RecipeNotFoundException $rnf) {
            throw new NotFoundHttpException($rnf->getMessage());
        }
    }
}
