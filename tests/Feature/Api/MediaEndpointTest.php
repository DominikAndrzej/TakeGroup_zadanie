<?php

use App\Models\Movie;
use App\Models\Serie;
use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

dataset('media_types', [
    'movies' => ['/api/movies', Movie::class],
    'series' => ['/api/series', Serie::class],
]);

test('returns paginated media with correct structure', function (string $url, string $modelClass) {
    // given
    $genre = Genre::factory()->create();
    $media = $modelClass::factory()->create();
    $media->genres()->attach($genre);

    // when
    $response = $this->getJson($url);

    $response->assertStatus(200)
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
            'links' => ['first', 'last', 'prev', 'next'],
            'meta' => ['current_page', 'total']
        ]);
})->with('media_types');
