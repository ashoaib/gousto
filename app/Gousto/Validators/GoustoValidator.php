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
     * @param $data
     *
     * @return mixed
     */
    public function validate($data)
    {
        $validation = Validator::make($data, static::$rules);

        $this->is_valid = !$validation->fails();

        if (!$this->is_valid) {
            $this->errors = $validation->errors();
        }

        return $this->is_valid;
    }

    /**
     * @return mixed
     */
    public function errors()
    {
        return $this->errors;
    }
}
