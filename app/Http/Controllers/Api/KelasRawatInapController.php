<?php

namespace App\Http\Controllers\Api;

use App\Models\KelasRawatInap;

class KelasRawatInapController extends ApiController
{
    public function index()
    {
        $this->authUser();

        return response()->json([
            'data' => KelasRawatInap::orderBy('jenis_kelas')->get(),
        ]);
    }
}
