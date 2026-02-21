<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MediaResource;
use App\Models\Serie;
use Illuminate\Http\Request;

class SerieController extends Controller
{
    //
    public function index()
    {
        $series = Serie::with('genres')->paginate();

        return MediaResource::collection($series);
    }
}
