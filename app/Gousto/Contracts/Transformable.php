<?php

namespace Gousto\Contracts;

/**
 * Interface Transformable
 */
interface Transformable
{
    /**
     * @param $data
     *
     * @return mixed
     */
    public function transform($data);
}
