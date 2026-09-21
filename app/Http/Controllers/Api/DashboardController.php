<?php

namespace App\Http\Controllers\Api;

use App\Models\FormJaminan;
use App\Models\MonitoringTagihan;
use App\Models\SuratKeterangan;
use App\Models\Visa;

class DashboardController extends ApiController
{
    public function index()
    {
        $this->authorizeRole('admin');

        return response()->json([
            'monitoring' => MonitoringTagihan::latest()->limit(3)->get(),
            'count_monitoring' => MonitoringTagihan::count(),
            'sudah' => FormJaminan::latest()->limit(3)->get(),
            'count_sudah' => FormJaminan::count(),
            'keterangan' => SuratKeterangan::latest()->limit(3)->get(),
            'count_keterangan' => SuratKeterangan::count(),
            'visa' => Visa::latest()->limit(3)->get(),
            'count_visa' => Visa::count(),
        ]);
    }
}
