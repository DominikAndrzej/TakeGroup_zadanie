<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class TmdbService
{
    protected string $apiToken;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiToken = config('services.tmdb.api_token');
        $this->baseUrl = config('services.tmdb.base_url');
    }

    /**
     * return all genres from Tmdb
     * @param string $locale
     * @return array
     * @throws ConnectionException
     */
    public function getGenres(string $locale): array
    {
        // TODO można zastąpić każde wywołanie api metodą z repository
        $movieGenres = Http::withToken($this->apiToken)
            ->get("$this->baseUrl/genre/movie/list", [
                'language' => $locale,
            ])->json()['genres'] ?? [];

        $tvGenres = Http::withToken($this->apiToken)
            ->get("$this->baseUrl/genre/tv/list", [
                'language' => $locale,
            ])->json()['genres'] ?? [];

        return collect($movieGenres)
            ->merge($tvGenres)
            ->unique('id')
            ->values()
            ->toArray();
    }

    /**
     * @throws ConnectionException
     */
    public function getDiscover(string $type, string $locale, int $page = 1): array
    {
        return Http::withToken($this->apiToken)
            ->get("$this->baseUrl/discover/$type", [
                'language' => $locale,
                'page' => $page,
            ])->json()['results'] ?? [];
    }

    /**
     * @throws ConnectionException
     */
    public function getDetails(string $type, int $id, string $locale): ?array
    {
        $response = Http::withToken($this->apiToken)
            ->get("$this->baseUrl/$type/$id", [
                'language' => $locale,
            ]);

        return $response->successful() ? $response->json() : null;
    }
}
