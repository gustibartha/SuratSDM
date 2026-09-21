<?php

namespace App\Http\Controllers\Admin;

use App\Models\FormJaminan;
use Illuminate\Http\Request;
use App\Models\HistoryRecord;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class ExportController extends Controller
{
    public function index(Request $request){

        $karyawan = $request->karyawan;
        $tanggal_mulai = $request->tanggal_mulai;
        $tanggal_selesai = $request->tanggal_selesai;

        if($karyawan){
            $data['forms'] = FormJaminan::join('karyawans', 'form_jaminans.id_karyawan', 'karyawans.id')
            ->join('kelas_rawat_inaps', 'karyawans.id_kelas_rawat_inap', 'kelas_rawat_inaps.id')  
            ->select('karyawans.*', 'form_jaminans.*', 'kelas_rawat_inaps.*', 'form_jaminans.id as id')      
            ->where('karyawans.status_karyawan', $karyawan)
            ->whereBetween('form_jaminans.created_at',[$tanggal_mulai,$tanggal_selesai])
            ->latest('form_jaminans.created_at')->get();

            $data['karyawan'] = $karyawan;
            $data['tanggal_mulai'] = $tanggal_mulai;
            $data['tanggal_selesai'] = $tanggal_selesai;
            return view('admin.export',$data);
        }else{
            $data['karyawan'] = '';
            $data['tanggal_mulai'] = '';
            $data['tanggal_selesai'] = '';
            return view('admin.export',$data);
        }

    }

    public function historyRecord(Request $request){
        $karyawan = $request->karyawan;
        $tanggal_mulai = $request->tanggal_mulai;
        $tanggal_selesai = $request->tanggal_selesai;

        if($karyawan){
            if($tanggal_mulai == $tanggal_selesai){

                $data['records'] = HistoryRecord::join('karyawans', 'history_records.karyawan_id', 'karyawans.id')
                ->select('karyawans.*', 'history_records.*','history_records.id as id')      
                ->where('karyawans.status_karyawan', $karyawan)
                ->where('history_records.created_at','>=',$tanggal_mulai)
                ->latest('history_records.created_at')->get();

            }else{
                $data['records'] = HistoryRecord::join('karyawans', 'history_records.karyawan_id', 'karyawans.id')
                ->select('karyawans.*', 'history_records.*','history_records.id as id')      
                ->where('karyawans.status_karyawan', $karyawan)
                ->whereBetween('history_records.created_at',[Carbon::parse($tanggal_mulai),Carbon::parse($tanggal_selesai)])
                ->latest('history_records.created_at')->get();
            }

            // dd($tanggal_mulai);
            $data['karyawan'] = $karyawan;
            $data['tanggal_mulai'] = $tanggal_mulai;
            $data['tanggal_selesai'] = $tanggal_selesai;
            // dd($data);
            return view('admin.export-record',$data);
        }else{
            $data['karyawan'] = '';
            $data['tanggal_mulai'] = '';
            $data['tanggal_selesai'] = '';
            return view('admin.export-record',$data);
        }



    }
}
