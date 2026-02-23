<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Translatable\HasTranslations;

/**
 * @mixin Builder
 */
class Genre extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['name', 'tmdb_id'];

    public array $translatable = ['name'];

    public function movies(): MorphToMany
    {
        return $this->morphedByMany(Movie::class, 'genreable');
    }

    public function series(): MorphToMany
    {
        return $this->morphedByMany(Serie::class, 'genreable');
    }
}
