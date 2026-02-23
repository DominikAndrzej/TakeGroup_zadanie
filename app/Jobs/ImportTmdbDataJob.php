<?php

namespace App\Jobs;

use App\Enums\SupportedLocale;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\Serie;
use App\Services\TmdbService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ImportTmdbDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct() {}

    /**
     * @throws ConnectionException
     */
    public function handle(TmdbService $service): void
    {
        try {
            Log::info('TMDB Import: data synchronization has started.');
            $this->syncGenres($service);
            Log::info('TMDB Import: genres synchronized successfully.');

            $this->syncMedia($service, Movie::class, 50);
            Log::info('TMDB Import: 50 movies synchronized successfully.');

            $this->syncMedia($service, Serie::class, 10);
            Log::info('TMDB Import: 10 movies synchronized successfully.');

        } catch (Exception $e) {
            Log::error('TMDB Import: Wystąpił błąd krytyczny: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            throw $e;
        }
    }

    /**
     * @throws ConnectionException
     */
    private function syncGenres(TmdbService $service): void
    {
        $locales = SupportedLocale::values();
        $genresMap = [];

        foreach ($locales as $locale) {
            foreach ($service->getGenres($locale) as $genreData) {
                $genresMap[$genreData['id']][$locale] = $genreData['name'];
            }
        }

        foreach ($genresMap as $tmdbId => $translations) {
            $genre = Genre::firstOrNew(['tmdb_id' => $tmdbId]);

            foreach ($translations as $locale => $name) {
                $genre->setTranslation('name', $locale, $name);
            }
            $genre->save();
        }
    }

    /**
     * @throws ConnectionException
     */
    private function syncMedia(TmdbService $service, string $modelClass, int $limit): void
    {
        $locales = SupportedLocale::values();
        $defaultLocale = SupportedLocale::default();
        $page = 1;
        $targetIds = [];
        $tmdbType = $modelClass::tmdbType();

        while (count($targetIds) < $limit) {
            $results = $service->getDiscover($tmdbType, $defaultLocale, $page);

            if (empty($results)) {
                Log::warning("TMDB Import: There's no records for {$modelClass} on page {$page}.");
                break;
            }

            foreach ($results as $item) {
                if (count($targetIds) >= $limit) break;

                if (!in_array($item['id'], $targetIds)) {
                    $targetIds[] = $item['id'];

                    $this->createOrUpdateMedia($modelClass, $item, $defaultLocale);
                }
            }
            $page++;
        }

        $otherLocales = array_diff($locales, [$defaultLocale]);

        foreach ($targetIds as $tmdbId) {
            foreach ($otherLocales as $locale) {
                $details = $service->getDetails($tmdbType, $tmdbId, $locale);

                if ($details) {
                    $this->createOrUpdateMedia($modelClass, $details, $locale);
                } else {
                    Log::error("TMDB Import: Couldn't get details for {$modelClass} Tmdb_id: {$tmdbId}, language: {$locale}.");
                }
            }
        }
    }

    private function createOrUpdateMedia(string $modelClass, array $item, string $locale): void
    {
        $model = $modelClass::firstOrNew(['tmdb_id' => $item['id']]);
        $mapping = $modelClass::tmdbMapping();

        $translatableFields = $model->translatable ?? [];

        foreach ($mapping as $dbField => $apiField) {
            if (!isset($item[$apiField])) continue;

            if (in_array($dbField, $translatableFields)) {
                $model->setTranslation($dbField, $locale, $item[$apiField]);
            } else {
                $model->$dbField = $item[$apiField];
            }
        }

        $model->save();

        if (isset($item['genre_ids'])) {
            $localGenreIds = Genre::whereIn('tmdb_id', $item['genre_ids'])->pluck('id');
            $model->genres()->syncWithoutDetaching($localGenreIds);
        }
    }
}
