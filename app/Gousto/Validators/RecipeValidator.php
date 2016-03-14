<?php

namespace Gousto\Validators;

/**
 * Class RecipeValidator
 * @package Gousto\Validators
 */
class RecipeValidator extends GoustoValidator
{
    /**
     * @var array
     */
    protected static $rules = [
        'cuisine' => 'string',
    ];
}
