<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(6),
            'content' => $this->faker->paragraphs(5, true),
            'image_path' => null,
            'summary' => $this->faker->sentence(10),
            'keywords' => ['uncinc', 'backend', 'assessment'],
        ];
    }
}
