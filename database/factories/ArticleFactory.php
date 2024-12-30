<?php

namespace Database\Factories;

use App\Enums\Sizes;
use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Certificate>
 */
class ArticleFactory extends Factory
{

    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'price' => fake()->numberBetween(10,90),
            'variants' =>  collect(Sizes::getValues())->reduce(function($arr, $item, $key){
                $arr[$item] = fake()->numberBetween(0,10);
                return $arr;
            }, [])
        ];
    }
}
