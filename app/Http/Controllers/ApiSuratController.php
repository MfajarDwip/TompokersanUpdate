<?php

namespace App\Http\Controllers;

use App\Models\MobileMasterSuratModel;

class ApiSuratController extends Controller
{
    public function surat()
    {
        $surat = MobileMasterSuratModel::orderByDesc('id_surat')->get();

        return response()->json([
            'message' => 'success',
            'data' => $surat,
        ]);
    }
}
