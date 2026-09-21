<?php

namespace App\Http\Controllers\Api;

use App\Models\FormJaminan;
use App\Models\HistoryRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ExportController extends ApiController
{
    /**
     * Unpaginated, date-filtered Form Jaminan rows for the "download as
     * Excel" screen. Mirrors the legacy Blade export filter exactly.
     */
    public function formJaminan(Request $request)
    {
        $this->authUser();

        $data = $request->validate([
            'karyawan' => 'required|in:karyawan_tetap,pensiunan',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
        ]);

        $forms = FormJaminan::with(['karyawan.kelasRawatInap', 'rumahSakit', 'jenisPemeriksaan'])
            ->whereHas('karyawan', function ($q) use ($data) {
                $q->where('status_karyawan', $data['karyawan']);
            })
            ->whereBetween('created_at', [$data['tanggal_mulai'], $data['tanggal_selesai']])
            ->latest()
            ->get();

        return response()->json(['data' => $forms]);
    }

    /**
     * Unpaginated, date-filtered History Record rows for export.
     */
    public function historyRecord(Request $request)
    {
        $this->authUser();

        $data = $request->validate([
            'karyawan' => 'required|in:karyawan_tetap,pensiunan',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
        ]);

        $start = Carbon::parse($data['tanggal_mulai'])->startOfDay();
        $end = Carbon::parse($data['tanggal_selesai'])->endOfDay();

        $records = HistoryRecord::with('karyawan')
            ->whereHas('karyawan', function ($q) use ($data) {
                $q->where('status_karyawan', $data['karyawan']);
            })
            ->whereBetween('created_at', [$start, $end])
            ->latest()
            ->get();

        return response()->json(['data' => $records]);
    }
}
