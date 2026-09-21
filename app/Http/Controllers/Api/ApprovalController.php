<?php

namespace App\Http\Controllers\Api;

use App\Models\FormJaminan;
use App\Models\SuratKeterangan;
use App\Models\Visa;

class ApprovalController extends ApiController
{
    /**
     * Everything currently awaiting a decision from the logged-in user's
     * role, across all three document types that share this approval chain
     * (Dokter -> Asisten Manager -> MKAD -> Senior Manager, minus the Dokter
     * step for Surat Keterangan and Visa).
     */
    public function index()
    {
        $user = $this->authUser();
        $role = $user->role;

        $data = [
            'form_jaminan' => [],
            'surat_keterangan' => [],
            'visa' => [],
        ];

        if (isset(FormJaminanController::ROLE_RANGKING[$role])) {
            $data['form_jaminan'] = FormJaminan::with(['karyawan.kelasRawatInap', 'rumahSakit', 'jenisPemeriksaan'])
                ->where('rangking', FormJaminanController::ROLE_RANGKING[$role])
                ->where('is_rejected', false)
                ->latest()
                ->get();
        }

        if (isset(SuratKeteranganController::ROLE_RANGKING[$role])) {
            $data['surat_keterangan'] = SuratKeterangan::with('karyawan')
                ->where('rangking', SuratKeteranganController::ROLE_RANGKING[$role])
                ->where('is_rejected', false)
                ->latest()
                ->get();
        }

        if (isset(VisaController::ROLE_RANGKING[$role])) {
            $data['visa'] = Visa::with(['karyawan', 'keluarga'])
                ->where('rangking', VisaController::ROLE_RANGKING[$role])
                ->where('is_rejected', false)
                ->latest()
                ->get();
        }

        return response()->json($data);
    }
}
