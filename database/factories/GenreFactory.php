<?php

namespace Database\Factories;

use App\Enums\SupportedLocale;
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
        $names = [];
            foreach (SupportedLocale::values() as $locale) {
                $names[$locale] = $this->faker->word();
            }

        return [
            'name' => $names,
        ];
    }
}
