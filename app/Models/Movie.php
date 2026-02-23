<?php

namespace App\Models;

class Movie extends Media
{
    public static function tmdbType(): string
    {
        return 'movie';
    }
}
