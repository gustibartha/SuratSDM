<?php

namespace App\Http\Controllers\Api;

use App\Models\RumahSakit;

class RumahSakitController extends ApiController
{
    public function index()
    {
        $this->authUser();

        return response()->json([
            'data' => RumahSakit::orderBy('nama_rumah_sakit')->get(),
        ]);
    }
}
