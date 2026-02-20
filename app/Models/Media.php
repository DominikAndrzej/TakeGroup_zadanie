<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Translatable\HasTranslations;

abstract class Media extends Model
{
    use HasFactory, HasTranslations;

    public array $translatable = ['title', 'overview'];

    protected $fillable = ['title', 'overview', 'release_date'];

    public function genres(): MorphToMany
    {
        return $this->morphToMany(Genre::class, 'genreable');
    }
}
