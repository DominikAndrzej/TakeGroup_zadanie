<?php

use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('returns genres with correct structure', function () {
    // given
    Genre::factory()->create();

    // when
    $response = $this->getJson('/api/genres');

    // then
    $response
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                ]
            ],
        ]);
});

test('returns genres with correct data', function () {
    // given
    /** @var Genre $genre */
    $genre = Genre::factory()->create();

    // when
    $response = $this->getJson('/api/genres');

    // then
    $response
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) =>
            $json->has('data', 1)
                ->where('data.0.id', $genre->id)
                ->where('data.0.name', $genre->name)
                ->etc()
        );
});

test('returns genres with translations', function (string $locale) {
    // given
    /** @var Genre $genre */
    $genre = Genre::factory()->create();

    $expectedName = $genre->getTranslation('name', $locale);

    // when
    $response = $this->getJson('/api/genres');

    // then
    $response
        ->assertOk()
        ->assertJsonPath('data.title', $expectedName);

})->with('locales');
