<?php

namespace Gousto\Validators;

/**
 * Class RatingValidator
 * @package Gousto\Validators
 */
class RatingValidator extends GoustoValidator
{
    /**
     * @var array
     */
    protected static $rules = [
        'recipe_id' => 'required|numeric',
        'rating' => 'required|numeric',
    ];
}
