<?php

namespace Database\Factories;

use App\Enums\SupportedLocale;
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
        $titles = [];
        $overviews = [];

        foreach (SupportedLocale::values() as $locale) {
            $titles[$locale] = $this->faker->sentence(3);
            $overviews[$locale] = $this->faker->paragraph();
        }

        return [
            'title' => $titles,
            'overview' => $overviews,
            'release_date' => $this->faker->date(),
        ];
    }
}
