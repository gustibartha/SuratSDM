<?php

namespace App\Http\Controllers\Api;

use App\Models\SuratKeterangan;
use Illuminate\Http\Request;

class SuratKeteranganController extends ApiController
{
    public const ROLE_RANGKING = [
        'asman' => 1,
        'mkad' => 2,
        'sm' => 3,
    ];

    private const NEXT_STATUS = [
        1 => 'Menunggu Persetujuan MBS',
        2 => 'Menunggu Persetujuan Senior Manager',
        3 => 'Sudah Disetujui oleh Senior Manager',
    ];

    public function approve($id)
    {
        $user = $this->authUser();
        $surat = SuratKeterangan::findOrFail($id);

        if ((self::ROLE_RANGKING[$user->role] ?? null) !== $surat->rangking) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $surat->rangking += 1;
        $surat->status = self::NEXT_STATUS[$surat->rangking - 1];
        $surat->save();

        return response()->json(['surat_keterangan' => $surat->load('karyawan')]);
    }

    public function reject($id)
    {
        $user = $this->authUser();
        $surat = SuratKeterangan::findOrFail($id);

        if ((self::ROLE_RANGKING[$user->role] ?? null) !== $surat->rangking) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $surat->rangking = 0;
        $surat->is_rejected = true;
        $surat->status = 'Surat Keterangan Ditolak';
        $surat->save();

        return response()->json(['surat_keterangan' => $surat->load('karyawan')]);
    }

    public function index()
    {
        $this->authUser();

        $surats = SuratKeterangan::with('karyawan')->latest()->paginate(25);

        return response()->json($surats);
    }

    public function store(Request $request)
    {
        $this->authUser();

        $data = $request->validate([
            'karyawan_id' => 'required|integer|exists:karyawans,id',
            'sifat' => 'nullable|string|max:255',
            'penerima' => 'nullable|string|max:255',
            'alamat_penerima' => 'nullable|string',
            'keperluan' => 'nullable|string',
            'tanggal_masuk_karyawan' => 'nullable|date',
        ]);

        $count = SuratKeterangan::whereYear('created_at', date('Y'))->count() + 1;
        $data['nomor_surat'] = sprintf('%02d', $count) . '/Ket.F/SDM/MKR/' . date('Y');
        $data['status'] = 'Menunggu Persetujuan Asisten Manager';

        $surat = SuratKeterangan::create($data)->fresh();

        return response()->json(['surat_keterangan' => $surat->load('karyawan')], 201);
    }

    public function show($id)
    {
        $this->authUser();

        $surat = SuratKeterangan::with('karyawan')->findOrFail($id);

        return response()->json(['surat_keterangan' => $surat]);
    }

    public function update(Request $request, $id)
    {
        $this->authUser();

        $surat = SuratKeterangan::findOrFail($id);

        $data = $request->validate([
            'sifat' => 'nullable|string|max:255',
            'penerima' => 'nullable|string|max:255',
            'alamat_penerima' => 'nullable|string',
            'keperluan' => 'nullable|string',
            'tanggal_masuk_karyawan' => 'nullable|date',
        ]);

        $surat->update($data);

        return response()->json(['surat_keterangan' => $surat->load('karyawan')]);
    }

    public function destroy($id)
    {
        $this->authUser();

        SuratKeterangan::destroy($id);

        return response()->json(['message' => 'deleted']);
    }
}
