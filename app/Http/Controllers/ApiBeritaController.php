<?php

namespace App\Http\Controllers;

use App\Models\MobileBeritaModel;

class ApiBeritaController extends Controller
{
    public function berita()
    {
        $berita = MobileBeritaModel::orderByDesc('id')->get();

        return response()->json([
            'message' => 'success',
            'data' => $berita,
        ]);
    }
    
     public function news()
    {
        $berita = MobileBeritaModel::orderByDesc('id')->take(5)->get();

        return response()->json([
            'message' => 'success',
            'data' => $berita,
        ]);
    }
}
