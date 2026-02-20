<?php

namespace Database\Factories;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Genre>
 */
class GenreFactory extends Factory
{
    protected $model = Genre::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => [
                'pl' => $this->faker->word(),
                'en' => $this->faker->word(),
                'de' => $this->faker->word(),
            ],
        ];
    }
}
