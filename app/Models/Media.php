<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Translatable\HasTranslations;

abstract class Media extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['title', 'overview', 'release_date', 'tmdb_id'];

    public array $translatable = ['title', 'overview'];

    public function genres(): MorphToMany
    {
        return $this->morphToMany(Genre::class, 'genreable');
    }

    public static function tmdbMapping(): array
    {
        return [
            'title' => 'title',
            'overview' => 'overview',
            'release_date' => 'release_date',
            'tmdb_id' => 'tmdb_id'
        ];
    }

    abstract public static function tmdbType(): string;
}
