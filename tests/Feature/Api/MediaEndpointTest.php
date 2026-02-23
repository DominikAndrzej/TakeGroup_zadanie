<?php

use App\Models\Movie;
use App\Models\Serie;
use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

uses(Tests\TestCase::class, RefreshDatabase::class);

dataset('media types', [
    'movies' => ['/api/movies', Movie::class],
    'series' => ['/api/series', Serie::class],
]);

test('returns media with correct structure', function (string $url, string $modelClass) {
    // given
    $media = $modelClass::factory()->createOne();
    /** @var Genre $genre */
    $genre = Genre::factory()->createOne();
    $media->genres()->attach($genre);

    // when
    $response = $this->getJson($url);

    // then
    $response
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'overview',
                    'release_date',
                    'genres' => [
                        '*' => ['id', 'name']
                    ]
                ]
            ],
        ]);
})->with('media types');

test('returns media with correct data', function (string $url, string $modelClass) {
    // given
    $media = $modelClass::factory()->createOne();
    /** @var Genre $genre */
    $genre = Genre::factory()->createOne();

    $media->genres()->attach($genre);

    // when
    $response = $this->getJson($url);

    // then
    $response
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) =>
            $json->has('data', 1)
                ->where('data.0.id', $media->id)
                ->where('data.0.title', $media->title)
                ->where('data.0.overview', $media->overview)
                ->where('data.0.genres.0.id', $genre->id)
                ->where('data.0.genres.0.name', $genre->name)
                ->etc()
        );
})->with('media types');

test('returns media with translations', function (string $url, string $modelClass, string $locale) {
    // given
    $media = $modelClass::factory()->createOne();
    /** @var Genre $genre */
    $genre = Genre::factory()->createOne();

    $media->genres()->attach($genre);

    $expectedTitle = $media->getTranslation('title', $locale);
    $expectedOverview = $media->getTranslation('overview', $locale);
    $expectedGenreName = $genre->getTranslation('name', $locale);

    // when
    $response = $this->getJson("$url", ['Accept-Language' => $locale]);

    // then
    $response
        ->assertOk()
        ->assertJsonFragment(['title' => $expectedTitle])
        ->assertJsonFragment(['overview' =>  $expectedOverview])
        ->assertJsonPath('data.0.genres.0.name', $expectedGenreName);

})->with('media types', 'locales');
