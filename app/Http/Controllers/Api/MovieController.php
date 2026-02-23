<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MediaResource;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{

    public function index(Request $request)
    {
        $perPage = $request->integer('per_page', 15);

        $movies = Movie::with('genres')->paginate($perPage);

        return MediaResource::collection($movies);
    }
}
