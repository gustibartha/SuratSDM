<?php

namespace App\Http\Controllers\Api;

use App\Models\HistoryRecord;
use Illuminate\Http\Request;

class HistoryRecordController extends ApiController
{
    public function index()
    {
        $this->authUser();

        $records = HistoryRecord::with('karyawan')->latest()->paginate(25);

        return response()->json($records);
    }

    private function rules(): array
    {
        return [
            'karyawan_id' => 'required|integer|exists:karyawans,id',
            'riwayat_penyakit' => 'nullable|string',
            'jenis_pengobatan' => 'nullable|string',
            'riwayat_obat' => 'nullable|string',
            'resume_medis' => 'nullable|string',
        ];
    }

    public function store(Request $request)
    {
        $this->authUser();

        $data = $request->validate($this->rules());

        $record = HistoryRecord::create($data);

        return response()->json(['record' => $record->load('karyawan')], 201);
    }

    public function show($id)
    {
        $this->authUser();

        $record = HistoryRecord::with('karyawan')->findOrFail($id);

        return response()->json(['record' => $record]);
    }

    public function update(Request $request, $id)
    {
        $this->authUser();

        $record = HistoryRecord::findOrFail($id);
        $data = $request->validate($this->rules());
        $record->update($data);

        return response()->json(['record' => $record->load('karyawan')]);
    }

    public function destroy($id)
    {
        $this->authUser();

        HistoryRecord::destroy($id);

        return response()->json(['message' => 'deleted']);
    }
}
