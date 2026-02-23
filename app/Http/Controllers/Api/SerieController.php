<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MediaResource;
use App\Models\Serie;
use Illuminate\Http\Request;

class SerieController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->integer('per_page', 15);

        $series = Serie::with('genres')->paginate($perPage);

        return MediaResource::collection($series);
    }
}
