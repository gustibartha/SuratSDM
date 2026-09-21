<?php

namespace App\Http\Controllers\ASMAN;

use PDF;
use Mail;
use Alert;
use App\User;
use App\Models\Visa;
use App\Http\Requests;
use App\Models\Karyawan;
use App\Models\RumahSakit;
use App\Models\FormJaminan;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\KelasRawatInap;
use App\Models\SuratKeterangan;
use App\Models\JenisPemeriksaan;
use App\Http\Controllers\Controller;
use App\Models\VisaKeluarga;

class VisaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {

        $perPage = 25;
        $data['visas'] = Visa::where('rangking','!=',3)
        ->orderBy('rangking', 'asc')
        ->latest()->paginate($perPage);

        return view('asman.visa.index', $data);

    }

    public function create()
    {
        $data['karyawans'] = Karyawan::all();
        return view('asman.visa.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        // dd($request);
        $count = Visa::whereYear('created_at',date('Y'))->count() + 1;
        $nomor = sprintf("%02d", $count);
        $nomor_surat= $nomor .'/VISA/SDM/MKR/'.date('Y');

        $visa = Visa::create([
            'nomor_surat' => $nomor_surat,
            'karyawan_id' => $request->karyawan_id,
            'jenis' => $request->jenis,
            'tujuan' => $request->tujuan,
            'alamat' => $request->alamat,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'negara_tujuan' => $request->negara_tujuan,
            'keperluan' => $request->keperluan,
            'status' => 'Menunggu persetujuan',
        ]);

        if($request->has('nama_keluarga')){
            $keluargas = [];

            foreach ($request->nama_keluarga as $index => $unit) {
                $keluargas[] = [
                    'nama' => $request->nama_keluarga[$index],
                    'hubungan' => $request->hubungan[$index],
                    'nomor_passport' => $request->passport[$index],
                ];
            }


            foreach ($keluargas as $keluarga) {
                VisaKeluarga::Create([
                    'visa_id' => $visa->id,
                    'nama' => $keluarga['nama'],
                    'hubungan' => $keluarga['hubungan'],
                    'nomor_passport' => $keluarga['nomor_passport'],
                ]);
            };
        }
        Alert::success('New ' . 'Surat Visa'. ' Created!' );

        return redirect('asman/visa');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $record = Visa::findOrFail($id);
        $keluargas = VisaKeluarga::where('visa_id', $record->id)->get();
        return view('asman.visa.show', compact('record','keluargas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $visa = Visa::findOrFail($id);
        $keluargas = VisaKeluarga::where('visa_id',$id)->get();
        $karyawan = Karyawan::all();

        $data['visa'] = $visa;
        $data['keluargas'] = $keluargas;
        $data['karyawans'] = $karyawan;
        return view('asman.visa.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        
        $visa =  Visa::where('id',$id)->first();
        $visa->karyawan_id = $request->karyawan_id;
        $visa->jenis = $request->jenis;
        $visa->tujuan = $request->tujuan;
        $visa->alamat = $request->alamat;
        $visa->tanggal_mulai = $request->tanggal_mulai;
        $visa->tanggal_selesai = $request->tanggal_selesai;
        $visa->negara_tujuan = $request->negara_tujuan;
        $visa->keperluan = $request->keperluan;
        $visa->save();

        VisaKeluarga::where('visa_id',$visa->id)->delete();

        if($request->has('nama_keluarga')){
            $keluargas = [];

            foreach ($request->nama_keluarga as $index => $unit) {
                $keluargas[] = [
                    'nama' => $request->nama_keluarga[$index],
                    'hubungan' => $request->hubungan[$index],
                    'nomor_passport' => $request->passport[$index],
                ];
            }


            foreach ($keluargas as $keluarga) {
                VisaKeluarga::Create([
                    'visa_id' => $visa->id,
                    'nama' => $keluarga['nama'],
                    'hubungan' => $keluarga['hubungan'],
                    'nomor_passport' => $keluarga['nomor_passport'],
                ]);
            };
        }

        Alert::success('New ' . 'Surat Visa'. ' Created!' );

        return redirect('asman/visa');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Alert::success('Record Deleted!' );
        Visa::destroy($id);
        VisaKeluarga::where('visa_id', $id)->delete();

        return redirect('asman/visa');
    }

    public function showPDF($id)
    {
        $visa = Visa::where('id',$id)->first();
        $karyawan = Karyawan::where('id',$visa->karyawan_id)->first();
        $visa->file = $visa->id.'.pdf';
        $visa->save();

        $sm = User::where('role','sm')->latest()->limit(1)->get();
        $keluargas = VisaKeluarga::where('visa_id',$visa->id)->get();

        return $data_pdf= PDF::loadView('asman/visa/pdf',compact(['visa','sm','keluargas']))->setPaper('a4', 'portrait')->stream();

    }
    public function approve($id)
    {
        $visa = Visa::findOrFail($id);
        $visa->rangking = 2;
        $visa->status = 'Menunggu Persetujuan MBS';
        $visa->save();

        Alert::success('Form Surat Keterangan Berhasil Disetujui' );

        return redirect()->back();
    }

    public function reject($id)
    {
        $visa = Visa::findOrFail($id);
        $visa->rangking = 0;
        $visa->is_rejected = true;
        $visa->status = 'Surat Visa Telah Ditolak!';
        $visa->save();

        Alert::success('Form Surat Keterangan Berhasil Ditolak' );

        return redirect()->back();
    }

    public function show_dashboard($id)
    {
        $formjaminan = FormJaminan::findOrFail($id);

        return view('asman.form-jaminan.show_dashboard', compact('formjaminan'));
    }
}
