<?php

namespace Gousto\Contracts;

use Illuminate\Support\Collection;

/**
 * Interface Repository
 * @package Gousto\Contracts
 */
interface Repository
{
    /**
     * @return Collection
     */
    public function all();

    /**
     * @param array $data
     *
     * @return array
     */
    //public function create(array $data);

    /**
     * @param $id
     * @param array $data
     *
     * @return array
     */
    //public function update($id, array $data);

    /**
     * @param $id
     *
     * @return bool
     */
    //public function delete($id);

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
}
