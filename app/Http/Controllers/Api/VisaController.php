<?php

namespace App\Http\Controllers\Api;

use App\Models\Visa;
use App\Models\VisaKeluarga;
use Illuminate\Http\Request;

class VisaController extends ApiController
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
        $visa = Visa::findOrFail($id);

        if ((self::ROLE_RANGKING[$user->role] ?? null) !== $visa->rangking) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $visa->rangking += 1;
        $visa->status = self::NEXT_STATUS[$visa->rangking - 1];
        $visa->save();

        return response()->json(['visa' => $visa->load(['karyawan', 'keluarga'])]);
    }

    public function reject($id)
    {
        $user = $this->authUser();
        $visa = Visa::findOrFail($id);

        if ((self::ROLE_RANGKING[$user->role] ?? null) !== $visa->rangking) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $visa->rangking = 0;
        $visa->is_rejected = true;
        $visa->status = 'Surat Visa Telah Ditolak!';
        $visa->save();

        return response()->json(['visa' => $visa->load(['karyawan', 'keluarga'])]);
    }

    public function index()
    {
        $this->authUser();

        $visas = Visa::with(['karyawan', 'keluarga'])->latest()->paginate(25);

        return response()->json($visas);
    }

    private function keluargaRules(): array
    {
        return [
            'keluarga' => 'nullable|array',
            'keluarga.*.nama' => 'required_with:keluarga|string|max:255',
            'keluarga.*.hubungan' => 'required_with:keluarga|string|max:255',
            'keluarga.*.nomor_passport' => 'nullable|string|max:255',
        ];
    }

    public function store(Request $request)
    {
        $this->authUser();

        $data = $request->validate(array_merge([
            'karyawan_id' => 'required|integer|exists:karyawans,id',
            'jenis' => 'nullable|string|max:255',
            'tujuan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'negara_tujuan' => 'nullable|string|max:255',
            'keperluan' => 'nullable|string|max:255',
        ], $this->keluargaRules()));

        $keluarga = $data['keluarga'] ?? [];
        unset($data['keluarga']);

        $count = Visa::whereYear('created_at', date('Y'))->count() + 1;
        $data['nomor_surat'] = sprintf('%02d', $count) . '/VISA/SDM/MKR/' . date('Y');
        $data['status'] = 'Menunggu persetujuan Asisten Manager';

        $visa = Visa::create($data)->fresh();

        foreach ($keluarga as $item) {
            VisaKeluarga::create([
                'visa_id' => $visa->id,
                'nama' => $item['nama'],
                'hubungan' => $item['hubungan'],
                'nomor_passport' => $item['nomor_passport'] ?? null,
            ]);
        }

        return response()->json(['visa' => $visa->load(['karyawan', 'keluarga'])], 201);
    }

    public function show($id)
    {
        $this->authUser();

        $visa = Visa::with(['karyawan', 'keluarga'])->findOrFail($id);

        return response()->json(['visa' => $visa]);
    }

    public function update(Request $request, $id)
    {
        $this->authUser();

        $visa = Visa::findOrFail($id);

        $data = $request->validate(array_merge([
            'karyawan_id' => 'required|integer|exists:karyawans,id',
            'jenis' => 'nullable|string|max:255',
            'tujuan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'negara_tujuan' => 'nullable|string|max:255',
            'keperluan' => 'nullable|string|max:255',
        ], $this->keluargaRules()));

        $keluarga = $data['keluarga'] ?? [];
        unset($data['keluarga']);

        $visa->update($data);

        VisaKeluarga::where('visa_id', $visa->id)->delete();
        foreach ($keluarga as $item) {
            VisaKeluarga::create([
                'visa_id' => $visa->id,
                'nama' => $item['nama'],
                'hubungan' => $item['hubungan'],
                'nomor_passport' => $item['nomor_passport'] ?? null,
            ]);
        }

        return response()->json(['visa' => $visa->load(['karyawan', 'keluarga'])]);
    }

    public function destroy($id)
    {
        $this->authUser();

        Visa::destroy($id);
        VisaKeluarga::where('visa_id', $id)->delete();

        return response()->json(['message' => 'deleted']);
    }
}
