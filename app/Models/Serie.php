<?php

namespace App\Models;

class Serie extends Media
{
    public static function tmdbMapping(): array
    {
        return [
            'title' => 'name',
            'overview' => 'overview',
            'release_date' => 'first_air_date',
            'tmdb_id' => 'id',
        ];
    }

    public static function tmdbType(): string
    {
        return 'tv';
    }
}
