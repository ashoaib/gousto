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
     * @param Validatable $validator
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

    /**
     * @param array $data
     *
     * @return array
     */
    public function createRecipe(array $data)
    {
        if ($this->validator->validate($data)) {
            return $this->repository->create($this->validator->getNulledData());
        } else {
            throw new BadRequestHttpException($this->validator->errors());
        }
    }

    /**
     * @param $id
     * @param array $data
     *
     * @return array
     */
    public function updateRecipe($id, array $data)
    {
        try {
            if ($this->validator->validate($data)) {
                return $this->repository->update($id, $this->validator->getNulledData());
            } else {
                throw new BadRequestHttpException($this->validator->errors());
            }
        } catch (RecipeNotFoundException $rnf) {
            throw new NotFoundHttpException($rnf->getMessage());
        }
    }

    /**
     * @param $id
     * @param array $data
     *
     * @return array
     */
    public function updatePartialRecipe($id, array $data)
    {
        try {
            if ($this->validator->validate($data)) {
                return $this->repository->update($id, $this->validator->getData());
            } else {
                throw new BadRequestHttpException($this->validator->errors());
            }
        } catch (RecipeNotFoundException $rnf) {
            throw new NotFoundHttpException($rnf->getMessage());
        }
    }
}
