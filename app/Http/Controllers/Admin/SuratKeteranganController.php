<?php

namespace App\Http\Controllers\Admin;

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
        $data['surats'] = SuratKeterangan::latest()->paginate($perPage);
        $data['karyawans'] = Karyawan::all();

        return view('admin.surat-keterangan.index', $data);

    }
    public function create()
    {
        return view('admin.form-jaminan.create');
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

        return redirect('admin/surat-keterangan');
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
        $suratketerangan = SuratKeterangan::findOrFail($id);

        return view('admin.surat-keterangan.show', compact('suratketerangan'));
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
        return view('admin.surat-keterangan.edit', $data);
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

        return redirect('admin/surat-keterangan');
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

        return redirect('admin/surat-keterangan');
    }

    public function showPDF($id)
    {
        $suratketerangan = SuratKeterangan::where('id',$id)->first();
        $karyawan = Karyawan::where('id',$suratketerangan->karyawan_id)->first();
        $suratketerangan->file = str_replace('/','-',$suratketerangan->nomor_surat).'-'.$karyawan->nama_karyawan.'.pdf';
        $suratketerangan->save();

        $sm = User::where('role','sm')->latest()->limit(1)->get();

        $data_pdf= PDF::loadView('admin/surat-keterangan/pdf',compact(['suratketerangan','sm']))->setPaper('a4', 'portrait')->save(public_path('surat-keterangan-pdf/'.$suratketerangan->file));


        Alert::success('Generate PDF Success' );
 
        return redirect('admin/surat-keterangan');
    }
    public function show_dashboard($id)
    {
        $formjaminan = FormJaminan::findOrFail($id);

        return view('admin.form-jaminan.show_dashboard', compact('formjaminan'));
    }
}
