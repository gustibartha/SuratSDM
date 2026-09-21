<?php

namespace App\Http\Controllers\Api;

use App\Models\Karyawan;
use Illuminate\Http\Request;

class KaryawanController extends ApiController
{
    /**
     * Lightweight list for select/combobox inputs (e.g. picking a karyawan
     * on the form jaminan form), including family member names so the
     * frontend doesn't need a second round-trip.
     */
    public function options(Request $request)
    {
        $this->authUser();

        $status = $request->query('status_karyawan', 'karyawan_tetap');

        $karyawan = Karyawan::when($status !== 'all', function ($query) use ($status) {
                $query->where('status_karyawan', $status);
            })
            ->with('kelasRawatInap')
            ->orderBy('nama_karyawan')
            ->get([
                'id', 'nama_karyawan', 'nid', 'istri', 'anak_1', 'anak_2', 'anak_3',
                'id_kelas_rawat_inap', 'status_karyawan',
            ]);

        return response()->json(['data' => $karyawan]);
    }

    public function index(Request $request)
    {
        $this->authorizeRole('admin');

        $status = $request->query('status_karyawan', 'karyawan_tetap');

        $karyawan = Karyawan::where('status_karyawan', $status)
            ->with('kelasRawatInap')
            ->orderBy('nama_karyawan')
            ->paginate(25);

        return response()->json($karyawan);
    }

    public function store(Request $request)
    {
        $this->authorizeRole('admin');

        $data = $request->validate([
            'nama_karyawan' => 'required|string|max:255',
            'nid' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'jenjang_jabatan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'istri' => 'nullable|string|max:255',
            'anak_1' => 'nullable|string|max:255',
            'anak_2' => 'nullable|string|max:255',
            'anak_3' => 'nullable|string|max:255',
            'status_karyawan' => 'required|string|max:255',
            'id_kelas_rawat_inap' => 'required|integer',
            'tgl_lahir_istri' => 'nullable|date',
            'tgl_lahir_anak_1' => 'nullable|date',
            'tgl_lahir_anak_2' => 'nullable|date',
            'tgl_lahir_anak_3' => 'nullable|date',
            'email' => 'nullable|email|max:255',
            'tanggal_masuk_karyawan' => 'nullable|date',
        ]);

        $karyawan = Karyawan::create($data);

        return response()->json(['karyawan' => $karyawan], 201);
    }

    public function show($id)
    {
        $this->authorizeRole('admin');

        $karyawan = Karyawan::with('kelasRawatInap')->findOrFail($id);

        return response()->json(['karyawan' => $karyawan]);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeRole('admin');

        $karyawan = Karyawan::findOrFail($id);

        $data = $request->validate([
            'nama_karyawan' => 'required|string|max:255',
            'nid' => 'required|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'jenjang_jabatan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'istri' => 'nullable|string|max:255',
            'anak_1' => 'nullable|string|max:255',
            'anak_2' => 'nullable|string|max:255',
            'anak_3' => 'nullable|string|max:255',
            'status_karyawan' => 'required|string|max:255',
            'id_kelas_rawat_inap' => 'required|integer',
            'tgl_lahir_istri' => 'nullable|date',
            'tgl_lahir_anak_1' => 'nullable|date',
            'tgl_lahir_anak_2' => 'nullable|date',
            'tgl_lahir_anak_3' => 'nullable|date',
            'email' => 'nullable|email|max:255',
            'tanggal_masuk_karyawan' => 'nullable|date',
        ]);

        $karyawan->update($data);

        return response()->json(['karyawan' => $karyawan]);
    }

    public function destroy($id)
    {
        $this->authorizeRole('admin');

        Karyawan::destroy($id);

        return response()->json(['message' => 'deleted']);
    }
}
