<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ArticleBody;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArticleBody>
 */
class ArticleBodyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = ArticleBody::class;

    public function definition()
    {
        $paragraphs = $this->faker->paragraphs(rand(2, 6));
        $title = $this->faker->realText(50);
        $post = "<h1>{$title}</h1>";
            foreach ($paragraphs as $para) {
                $post .= "<p>{$para}</p>";
            }

        return [
            'body' => $post,
            'article_id' => 1
        ];
    }
}
