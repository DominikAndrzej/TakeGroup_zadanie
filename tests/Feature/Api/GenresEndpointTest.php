<?php

use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

test('returns paginated list of genres with correct structure', function () {
    // given
    Genre::factory()->create();

    // when
    $response = $this->getJson('/api/genres');

    $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                ]
            ],
            'links',
            'meta'
        ]);
});
