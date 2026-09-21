<?php

namespace App\Http\Controllers\Api;

use App\Models\FormJaminan;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class FormJaminanController extends ApiController
{
    /**
     * Approval chain: rangking N is awaiting this role's decision; approving
     * advances to rangking N+1 with the given status label.
     */
    public const ROLE_RANGKING = [
        'dokter' => 1,
        'asman' => 2,
        'mkad' => 3,
        'sm' => 4,
    ];

    private const NEXT_STATUS = [
        1 => 'Menunggu Persetujuan Asisten Manager',
        2 => 'Menunggu Persetujuan MBS',
        3 => 'Menunggu Persetujuan Senior Manager',
        4 => 'Sudah Disetujui Senior Manager',
    ];

    public function approve($id)
    {
        $user = $this->authUser();
        $formJaminan = FormJaminan::findOrFail($id);

        if ((self::ROLE_RANGKING[$user->role] ?? null) !== $formJaminan->rangking) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $formJaminan->rangking += 1;
        $formJaminan->status_pengajuan = self::NEXT_STATUS[$formJaminan->rangking - 1];
        $formJaminan->save();

        return response()->json([
            'form_jaminan' => $formJaminan->load(['karyawan.kelasRawatInap', 'rumahSakit', 'jenisPemeriksaan']),
        ]);
    }

    public function reject($id)
    {
        $user = $this->authUser();
        $formJaminan = FormJaminan::findOrFail($id);

        if ((self::ROLE_RANGKING[$user->role] ?? null) !== $formJaminan->rangking) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $formJaminan->rangking = 0;
        $formJaminan->is_rejected = 1;
        $formJaminan->status_pengajuan = 'Surat Jaminan Ditolak';
        $formJaminan->save();

        return response()->json([
            'form_jaminan' => $formJaminan->load(['karyawan.kelasRawatInap', 'rumahSakit', 'jenisPemeriksaan']),
        ]);
    }

    public function index(Request $request)
    {
        $this->authUser();

        $status = $request->query('status_karyawan', 'karyawan_tetap');
        $jenisSurat = $request->query('jenis_surat', 'personal');

        $formJaminan = FormJaminan::with(['karyawan.kelasRawatInap', 'rumahSakit', 'jenisPemeriksaan'])
            ->whereHas('karyawan', function ($q) use ($status) {
                $q->where('status_karyawan', $status);
            })
            ->where('jenis_surat', $jenisSurat)
            ->latest()
            ->paginate(25);

        return response()->json($formJaminan);
    }

    public function store(Request $request)
    {
        $this->authUser();

        $data = $request->validate([
            'id_karyawan' => 'required|integer|exists:karyawans,id',
            'jenis_surat' => 'required|in:personal,keluarga',
            'nama_pasien' => 'required|string|max:255',
            'hubungan_keluarga' => 'required|string|max:255',
            'id_jenis_pemeriksaan' => 'required|integer|exists:jenis_pemeriksaans,id',
            'id_rumah_sakit' => 'required|integer|exists:rumah_sakits,id',
            'biaya_rumah_sakit' => 'nullable|integer',
        ]);

        $karyawan = Karyawan::findOrFail($data['id_karyawan']);

        $latest = FormJaminan::latest()->first();
        $nomor = $latest ? $latest->id + 1 : 1;
        $statusKode = $karyawan->status_karyawan === 'karyawan_tetap' ? 'KT' : 'PS';

        $data['nomor_surat'] = $nomor . '/' . $statusKode . '/450/SDM/' . date('Y');
        $data['status_email'] = 0;
        $data['status_pengajuan'] = 'Menunggu Persetujuan Dokter';

        $formJaminan = FormJaminan::create($data)->fresh();

        return response()->json([
            'form_jaminan' => $formJaminan->load(['karyawan.kelasRawatInap', 'rumahSakit', 'jenisPemeriksaan']),
        ], 201);
    }

    public function show($id)
    {
        $this->authUser();

        $formJaminan = FormJaminan::with(['karyawan.kelasRawatInap', 'rumahSakit', 'jenisPemeriksaan'])
            ->findOrFail($id);

        return response()->json(['form_jaminan' => $formJaminan]);
    }

    public function update(Request $request, $id)
    {
        $this->authUser();

        $formJaminan = FormJaminan::findOrFail($id);

        $data = $request->validate([
            'id_karyawan' => 'required|integer|exists:karyawans,id',
            'jenis_surat' => 'required|in:personal,keluarga',
            'nama_pasien' => 'required|string|max:255',
            'hubungan_keluarga' => 'required|string|max:255',
            'id_jenis_pemeriksaan' => 'required|integer|exists:jenis_pemeriksaans,id',
            'id_rumah_sakit' => 'required|integer|exists:rumah_sakits,id',
            'biaya_rumah_sakit' => 'nullable|integer',
        ]);

        $formJaminan->update($data);

        return response()->json([
            'form_jaminan' => $formJaminan->load(['karyawan.kelasRawatInap', 'rumahSakit', 'jenisPemeriksaan']),
        ]);
    }

    public function destroy($id)
    {
        $this->authUser();

        FormJaminan::destroy($id);

        return response()->json(['message' => 'deleted']);
    }
}
