<?php

namespace Gousto\Providers;

use Gousto\Models\Rating;
use Gousto\Repositories\RatingRepository;
use Gousto\Services\RatingService;
use Gousto\Validators\RatingValidator;
use Illuminate\Support\ServiceProvider;
use Gousto\Repositories\RecipeRepository;
use Gousto\Models\Recipe;
use Gousto\Services\RecipeService;
use Gousto\Validators\RecipeValidator;
use Gousto\Contracts\Validatable;
use Gousto\Contracts\Transformable;
use Gousto\Http\Controllers\RecipeController;
use Gousto\Transformers\RecipeTransformer;

/**
 * Class GoustoServiceProvider
 * @package Gousto\Providers
 */
class GoustoServiceProvider extends ServiceProvider
{
    /**
     * @inheritdoc
     */
    public function boot()
    {
        $routes_file = __DIR__ . '/../Http/routes.php';
        if (file_exists($routes_file)) {
            require_once $routes_file;
        }
    }

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->registerServices();
        $this->registerRepositories();
        $this->registerModels();
        $this->registerValidators();
        $this->registerTransformers();
    }

    /**
     * Registers Gousto services.
     */
    protected function registerServices()
    {
        $this->app->bind(RecipeService::class, function ($app) {
            return new RecipeService(
                $app[RecipeRepository::class],
                $app[RecipeValidator::class]
            );
        });

        $this->app->bind(RatingService::class, function ($app) {
            return new RatingService(
                $app[RatingRepository::class],
                $app[RatingValidator::class],
                $app[RecipeService::class]
            );
        });
    }

    /**
     * Registers Gousto repositories.
     */
    protected function registerRepositories()
    {
        $this->app->bind(RecipeRepository::class, function ($app) {
            return new RecipeRepository(
                $app[Recipe::class]
            );
        });

        $this->app->bind(RatingRepository::class, function ($app) {
            return new RatingRepository(
                $app[Rating::class]
            );
        });
    }

    /**
     * Registers Gousto models.
     */
    protected function registerModels()
    {
        // TODO: Implement
    }

    /**
     * Registers Gousto validators.
     */
    protected function registerValidators()
    {
        $this->app->bind(Validatable::class, function ($app) {
            return $app['validator'];
        });
    }

    /**
     * Registers Gousto transformers.
     */
    protected function registerTransformers()
    {
        $this->app->when(RecipeController::class)
            ->needs(Transformable::class)
            ->give(RecipeTransformer::class);
    }
}
