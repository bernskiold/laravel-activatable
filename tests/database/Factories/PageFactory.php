<?php

namespace Bernskiold\LaravelActivatable\Tests\Database\Factories;

use Bernskiold\LaravelActivatable\Concerns\ActivatableFactory;
use Bernskiold\LaravelActivatable\Tests\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    use ActivatableFactory;

    protected $model = Page::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
        ];
    }
}
