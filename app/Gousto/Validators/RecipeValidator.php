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
        'box_type' => 'string',
        'title' => 'string',
        'slug' => 'string',
        'short_title' => 'string',
        'marketing_description' => 'string',
        'calories_kcal' => 'numeric',
        'protein_grams' => 'numeric',
        'fat_grams' => 'numeric',
        'carbs_grams' => 'numeric',
        'bulletpoint1' => 'string',
        'bulletpoint2' => 'string',
        'bulletpoint3' => 'string',
        'recipe_diet_type_id' => 'string',
        'season' => 'string',
        'base' => 'string',
        'protein_source' => 'string',
        'preparation_time_minutes' => 'numeric',
        'shelf_life_days' => 'numeric',
        'equipment_needed' => 'string',
        'origin_country' => 'string',
        'recipe_cuisine' => 'string',
        'in_your_box' => 'string',
        'gousto_reference' => 'integer',
    ];
}
