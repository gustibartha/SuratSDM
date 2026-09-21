<?php

namespace App\Http\Controllers\Api;

use App\Models\FormJaminan;
use App\Models\MonitoringTagihan;
use Illuminate\Http\Request;

class MonitoringTagihanController extends ApiController
{
    public function index(Request $request)
    {
        $this->authUser();

        $monitoringTagihan = MonitoringTagihan::with(['formJaminan.karyawan', 'formJaminan.rumahSakit'])
            ->latest()
            ->paginate(25);

        return response()->json($monitoringTagihan);
    }

    /**
     * Form jaminan eligible to get a new monitoring tagihan: already approved
     * by MKAD and not already tied to an existing monitoring tagihan record.
     */
    public function availableFormJaminan()
    {
        $this->authUser();

        $usedIds = MonitoringTagihan::pluck('id_form_jaminan');

        $formJaminan = FormJaminan::with('karyawan')
            ->where('status_pengajuan', 'Sudah Disetujui MKAD')
            ->whereNotIn('id', $usedIds)
            ->latest()
            ->get();

        return response()->json(['data' => $formJaminan]);
    }

    public function store(Request $request)
    {
        $this->authUser();

        $data = $request->validate([
            'id_form_jaminan' => 'required|integer|exists:form_jaminans,id',
            'tanggal_tagihan' => 'nullable|date',
            'no_tagihan' => 'nullable|string|max:255',
            'jumlah' => 'required|integer',
            'tanggal_pembayaran' => 'nullable|date',
            'tanggal_realisasi_perawatan' => 'nullable|date',
            'tanggal_realisasi_perawatan_akhir' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'status_pembayaran' => 'nullable|in:Belum Di Bayar,Sudah Di Bayar',
        ]);

        $data['status_pembayaran'] = $data['status_pembayaran'] ?? 'Belum Di Bayar';

        $monitoringTagihan = MonitoringTagihan::create($data);

        return response()->json([
            'monitoring_tagihan' => $monitoringTagihan->load(['formJaminan.karyawan', 'formJaminan.rumahSakit']),
        ], 201);
    }

    public function show($id)
    {
        $this->authUser();

        $monitoringTagihan = MonitoringTagihan::with(['formJaminan.karyawan', 'formJaminan.rumahSakit'])
            ->findOrFail($id);

        return response()->json(['monitoring_tagihan' => $monitoringTagihan]);
    }

    public function update(Request $request, $id)
    {
        $this->authUser();

        $monitoringTagihan = MonitoringTagihan::findOrFail($id);

        $data = $request->validate([
            'tanggal_tagihan' => 'nullable|date',
            'no_tagihan' => 'nullable|string|max:255',
            'jumlah' => 'required|integer',
            'tanggal_pembayaran' => 'nullable|date',
            'tanggal_realisasi_perawatan' => 'nullable|date',
            'tanggal_realisasi_perawatan_akhir' => 'nullable|date',
            'keterangan' => 'nullable|string',
            'status_pembayaran' => 'nullable|in:Belum Di Bayar,Sudah Di Bayar',
        ]);

        $monitoringTagihan->update($data);

        return response()->json([
            'monitoring_tagihan' => $monitoringTagihan->load(['formJaminan.karyawan', 'formJaminan.rumahSakit']),
        ]);
    }

    public function destroy($id)
    {
        $this->authUser();

        MonitoringTagihan::destroy($id);

        return response()->json(['message' => 'deleted']);
    }
}
