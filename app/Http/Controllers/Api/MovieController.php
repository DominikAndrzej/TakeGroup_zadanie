<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MediaResource;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    //
    public function index()
    {
        $movies = Movie::with('genres')->paginate();

        return MediaResource::collection($movies);
    }
}
