<?php

namespace Gousto\Contracts;

/**
 * Interface Validatable
 * @package Gousto\Contracts
 */
interface Validatable
{
    /**
     * @param $data
     *
     * @return mixed
     */
    public function validate($data);

    /**
     * @return mixed
     */
    public function errors();
}
