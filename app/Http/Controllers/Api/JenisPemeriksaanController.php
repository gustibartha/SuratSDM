<?php

namespace App\Http\Controllers\Api;

use App\Models\JenisPemeriksaan;

class JenisPemeriksaanController extends ApiController
{
    public function index()
    {
        $this->authUser();

        return response()->json([
            'data' => JenisPemeriksaan::orderBy('jenis_pemeriksaan')->get(),
        ]);
    }
}
