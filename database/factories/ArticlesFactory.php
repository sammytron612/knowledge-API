<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Articles;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Articles>
 */
class ArticlesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Articles::class;

    public function definition()
    {

        return [
            'title' => $this->faker->name,
            'section_id' => 1,
            'views' => $this->faker->randomDigit(),
            'kb' => $this->faker->randomNumber(5, false),
            'author' => 1
        ];

    }
}
