<?php

namespace App\Http\Controllers\Api;

use App\Models\Karyawan;
use App\Models\KuitansiNameAlias;
use Illuminate\Http\Request;

class KuitansiNameAliasController extends ApiController
{
    public function index()
    {
        $this->authUser();

        return response()->json([
            'data' => KuitansiNameAlias::with('karyawan:id,nama_karyawan,nid,status_karyawan')
                ->orderBy('alias_name')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $user = $this->authUser();

        $data = $request->validate([
            'alias_name' => 'required|string|max:255',
            'status_karyawan' => 'required|in:karyawan_tetap,pensiunan',
            'karyawan_id' => 'required|integer',
        ]);

        $aliasKey = KuitansiSyncController::matchKey($data['alias_name']);
        if ($aliasKey === '') {
            return response()->json(['message' => 'Nama tidak valid.'], 422);
        }

        // status_karyawan is the sheet tab the spelling came from; the target
        // may be of either status, e.g. a KARYAWAN-tab claim from someone
        // who has since retired and now only has a pensiunan record.
        $karyawan = Karyawan::find($data['karyawan_id']);

        if (! $karyawan) {
            return response()->json(['message' => 'Karyawan tidak ditemukan.'], 422);
        }

        $alias = KuitansiNameAlias::updateOrCreate(
            [
                'status_karyawan' => $data['status_karyawan'],
                'alias_key' => $aliasKey,
            ],
            [
                'alias_name' => $data['alias_name'],
                'karyawan_id' => $karyawan->id,
                'created_by' => $user->id,
            ]
        );

        return response()->json(['data' => $alias->load('karyawan:id,nama_karyawan,nid,status_karyawan')], 201);
    }

    public function destroy($id)
    {
        $this->authUser();

        KuitansiNameAlias::findOrFail($id)->delete();

        return response()->json(['message' => 'deleted']);
    }
}
