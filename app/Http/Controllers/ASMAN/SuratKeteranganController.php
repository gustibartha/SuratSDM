<?php

namespace App\Http\Controllers\ASMAN;

use PDF;
use Mail;
use Alert;
use App\User;
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

class SuratKeteranganController extends Controller
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
        $data['surats'] = SuratKeterangan::where('rangking','!=',3)
        ->orderBy('rangking', 'asc')
        ->latest()->paginate($perPage);

        return view('asman.surat-keterangan.index', $data);

    }
    public function create()
    {
        return view('asman.form-jaminan.create');
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
        
        $count = SuratKeterangan::whereYear('created_at',date('Y'))->count() + 1;
        $nomor = sprintf("%02d", $count);
        $karyawan = Karyawan::where('id',$request->karyawan_id)->first();
        // dd($dokter[0]['name']);
        $request['nomor_surat']= $nomor .'/Ket.F/SDM/MKR/'.date('Y');
        $request['status'] = 'Menunggu Persetujuan Asisten Manager';
        $requestData = $request->all();
        $data_karyawan = SuratKeterangan::create($requestData);

        Alert::success('New ' . 'Surat Keterangan'. ' Created!' );

        return redirect('asman/surat-keterangan');
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
        $record = SuratKeterangan::findOrFail($id);

        return view('asman.surat-keterangan.show', compact('record'));
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
        $suratketerangan = SuratKeterangan::findOrFail($id);
        $karyawan = Karyawan::all();

        $data['suratketerangan'] = $suratketerangan;
        $data['karyawans'] = $karyawan;
        return view('asman.surat-keterangan.edit', $data);
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
        
        $requestData = $request->except('karyawan_id');
        
        $suratketerangan = SuratKeterangan::findOrFail($id);
        Alert::success('Record Updated!' );
        $suratketerangan->update($requestData);

        return redirect('asman/surat-keterangan');
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
        SuratKeterangan::destroy($id);

        return redirect('asman/surat-keterangan');
    }

    public function showPDF($id)
    {
        $suratketerangan = SuratKeterangan::where('id',$id)->first();
        $karyawan = Karyawan::where('id',$suratketerangan->karyawan_id)->first();
        $suratketerangan->file = str_replace('/','-',$suratketerangan->nomor_surat).'-'.$karyawan->nama_karyawan.'.pdf';
        $suratketerangan->save();

        $sm = User::where('role','sm')->latest()->limit(1)->get();

        return $data_pdf= PDF::loadView('asman/surat-keterangan/pdf',compact(['suratketerangan','sm']))->setPaper('a4', 'portrait')->stream();
    }

    public function approve($id)
    {
        $suratketerangan = Suratketerangan::findOrFail($id);
        $suratketerangan->rangking = 2;
        $suratketerangan->status = 'Menunggu Persetujuan MBS';
        $suratketerangan->save();

        Alert::success('Form Surat Keterangan Berhasil Disetujui' );

        return redirect()->back();
    }

    public function reject($id)
    {
        $suratketerangan = Suratketerangan::findOrFail($id);
        $suratketerangan->rangking = 0;
        $suratketerangan->is_rejected = true;
        $suratketerangan->status = 'Surat Keterangan Ditolak';
        $suratketerangan->save();

        Alert::success('Form Surat Keterangan Berhasil Ditolak' );

        return redirect()->back();
    }

    public function show_dashboard($id)
    {
        $formjaminan = FormJaminan::findOrFail($id);

        return view('asman.form-jaminan.show_dashboard', compact('formjaminan'));
    }
}
