<?php

namespace Gousto\Transformers;

use Gousto\Contracts\Transformable;
use League\Fractal\TransformerAbstract;

/**
 * Class RecipeTransformer
 * @package Gousto\Transformers
 */
class RecipeTransformer extends TransformerAbstract implements Transformable
{
    /**
     * @param $data
     *
     * @return mixed
     */
    public function transform($data)
    {
        return (array) $data;
    }
}
