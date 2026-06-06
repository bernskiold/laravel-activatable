<?php

namespace Bernskiold\LaravelActivatable\Tests\Factories;

use Bernskiold\LaravelActivatable\Concerns\ActivatableFactory;
use Bernskiold\LaravelActivatable\Tests\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    use ActivatableFactory;

    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
        ];
    }
}
