<?php

namespace Gousto\Contracts;

use Illuminate\Support\Collection;

/**
 * Interface Model
 * @package Gousto\Contracts
 */
interface Model
{
    /**
     * @return Collection
     */
    public function all();

    /**
     * @param $id
     *
     * @return array
     */
    public function find($id);

    /**
     * @param array $criteria
     *
     * @return Collection
     */
    public function findBy(array $criteria);

    /**
     * @param $data
     *
     * @return array
     */
    //public function create($data);

    /**
     * @return array
     */
    //public function save();
}
