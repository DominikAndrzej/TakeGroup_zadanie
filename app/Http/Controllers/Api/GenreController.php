<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GenreResource;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    //
    public function index(Request $request)
    {
        $perPage = $request->integer('per_page', 15);

        $genres = Genre::query()->paginate($perPage);

        return GenreResource::collection($genres);
    }
}
