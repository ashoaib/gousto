<?php

namespace Gousto\Validators;

use Gousto\Contracts\Validatable;
use Illuminate\Support\Facades\Validator;

/**
 * Class GoustoValidator
 * @package Gousto\Validators
 */
class GoustoValidator implements Validatable
{
    /**
     * @var array
     */
    protected static $rules = [];

    /**
     * @var array
     */
    protected $errors;

    /**
     * @var boolean
     */
    protected $is_valid;

    /**
     * @var array
     */
    protected $data;

    /**
     * @inheritdoc
     */
    public function validate($data)
    {
        $validation = Validator::make($data, static::$rules);

        $this->is_valid = !$validation->fails();

        if (!$this->is_valid) {
            $this->errors = $validation->errors();
        } else {
            $this->intersectData($data);
        }

        return $this->is_valid;
    }

    /**
     * @inheritdoc
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @inheritdoc
     */
    public function getNulledData()
    {
        $diff = array_keys(array_diff_key(static::$rules, $this->data));
        foreach ($diff as $k) {
            $this->data[$k] = null;
        }
        return $this->data;
    }

    /**
     * @param $data
     */
    protected function intersectData($data)
    {
        $this->data = array_intersect_key($data, static::$rules);
    }
}
