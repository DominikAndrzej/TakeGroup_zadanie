<?php

namespace Database\Factories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Media>
 */
class MediaFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => [
                'pl' => $this->faker->sentence(3),
                'en' => $this->faker->sentence(3),
                'de' => $this->faker->sentence(3),
            ],
            'overview' => [
                'pl' => $this->faker->paragraph(),
                'en' => $this->faker->paragraph(),
                'de' => $this->faker->paragraph(),
            ],
            'release_date' => $this->faker->date(),
        ];
    }
}
