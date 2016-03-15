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
     * @return boolean
     */
    public function validate($data);

    /**
     * @return mixed
     */
    public function errors();

    /**
     * @return array
     */
    public function getData();

    /**
     * @return array
     */
    public function getNulledData();

    /**
     * @param $field
     * @param $value
     */
    public function addRule($field, $value);
}
