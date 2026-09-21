<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Alert;
use App\Models\FormJaminan;
use App\Models\Karyawan;
use App\Models\KelasRawatInap;
use App\Models\JenisPemeriksaan;
use App\Models\RumahSakit;
use App\User;
use Illuminate\Http\Request;
use Mail;
use PDF;
use Illuminate\Support\Str;

class FormJaminanController extends Controller
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

        $formjaminan_personal = FormJaminan::join('karyawans', 'form_jaminans.id_karyawan', 'karyawans.id')
                                    ->join('kelas_rawat_inaps', 'karyawans.id_kelas_rawat_inap', 'kelas_rawat_inaps.id')  
                                    ->select('karyawans.*', 'form_jaminans.*', 'kelas_rawat_inaps.*', 'form_jaminans.id as id')      
                                    ->where('karyawans.status_karyawan', 'karyawan_tetap')
                                    ->where('form_jaminans.jenis_surat', 'personal')->latest('form_jaminans.created_at')->paginate($perPage);
        $formjaminan_keluarga = FormJaminan::join('karyawans', 'form_jaminans.id_karyawan', 'karyawans.id')
                                    ->join('kelas_rawat_inaps', 'karyawans.id_kelas_rawat_inap', 'kelas_rawat_inaps.id')  
                                    ->select('karyawans.*', 'form_jaminans.*', 'kelas_rawat_inaps.*', 'form_jaminans.id as id')      
                                    ->where('karyawans.status_karyawan', 'karyawan_tetap')
                                    ->where('form_jaminans.jenis_surat', 'keluarga')->latest('form_jaminans.created_at')->paginate($perPage);

        $karyawan = Karyawan::where('status_karyawan', 'karyawan_tetap')->get();
        $kelasrawatinap = KelasRawatInap::pluck('jenis_kelas', 'id');
        $jenisPemeriksaan = JenisPemeriksaan::pluck('jenis_pemeriksaan', 'id');
        $rumahSakit = RumahSakit::all();
        $data['formjaminan_personal'] = $formjaminan_personal;
        $data['formjaminan_keluarga'] = $formjaminan_keluarga;
        $data['karyawan'] = $karyawan;
        $data['kelasRawatInap'] = $kelasrawatinap;
        $data['jenisPemeriksaan'] = $jenisPemeriksaan;
        $data['rumahSakit'] = $rumahSakit;
        return view('admin.form-jaminan.index', $data);
    }
    // public function index_pensiunan(Request $request)
    // {
    //     $jeniskaryawan = Karyawan::where('status_karyawan','pensiunan')->get();
    //     foreach($jeniskaryawan as $item){
    //         $id_karyawan = $item->id;
    //     }
    //     $perPage = 25;
    //     $formjaminan = FormJaminan::where('id_karyawan',$id_karyawan)->latest()->paginate($perPage);
    //     $karyawan = Karyawan::pluck('nama_karyawan', 'id');
    //     $kelasrawatinap = KelasRawatInap::pluck('jenis_kelas', 'id');
    //     $jenisPemeriksaan = JenisPemeriksaan::pluck('jenis_pemeriksaan', 'id');
    //     $rumahSakit = RumahSakit::pluck('nama_rumah_sakit', 'id');
    //     $data['formjaminan'] = $formjaminan;
    //     $data['karyawan'] = $karyawan;
    //     $data['kelasRawatInap'] = $kelasrawatinap;
    //     $data['jenisPemeriksaan'] = $jenisPemeriksaan;
    //     $data['rumahSakit'] = $rumahSakit;
    //     return view('admin.form-jaminan.index_pensiunan', $data);
    // }

    public function index_pensiunan(Request $request)
    {
        $perPage = 25;
        $formjaminan_personal = FormJaminan::join('karyawans', 'form_jaminans.id_karyawan', 'karyawans.id')
                                    ->join('kelas_rawat_inaps', 'karyawans.id_kelas_rawat_inap', 'kelas_rawat_inaps.id')  
                                    ->select('karyawans.*', 'form_jaminans.*', 'kelas_rawat_inaps.*', 'form_jaminans.id as id')      
                                    ->where('karyawans.status_karyawan', 'pensiunan')
                                    ->where('form_jaminans.jenis_surat', 'personal')->latest('form_jaminans.created_at')->paginate($perPage);
        $formjaminan_keluarga = FormJaminan::join('karyawans', 'form_jaminans.id_karyawan', 'karyawans.id')
                                    ->join('kelas_rawat_inaps', 'karyawans.id_kelas_rawat_inap', 'kelas_rawat_inaps.id')  
                                    ->select('karyawans.*', 'form_jaminans.*', 'kelas_rawat_inaps.*', 'form_jaminans.id as id')      
                                    ->where('karyawans.status_karyawan', 'pensiunan')
                                    ->where('form_jaminans.jenis_surat', 'keluarga')->latest('form_jaminans.created_at')->paginate($perPage);

        $karyawan = Karyawan::where('status_karyawan', 'pensiunan')->get();
        $kelasrawatinap = KelasRawatInap::pluck('jenis_kelas', 'id');
        $jenisPemeriksaan = JenisPemeriksaan::pluck('jenis_pemeriksaan', 'id');
        $rumahSakit = RumahSakit::all();
        $data['formjaminan_personal'] = $formjaminan_personal;
        $data['formjaminan_keluarga'] = $formjaminan_keluarga;
        $data['karyawan'] = $karyawan;
        $data['kelasRawatInap'] = $kelasrawatinap;
        $data['jenisPemeriksaan'] = $jenisPemeriksaan;
        $data['rumahSakit'] = $rumahSakit;
        return view('admin.form-jaminan.index_pensiunan', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
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

        $latest_form = FormJaminan::latest()->first();
        $dokter = User::where('role','dokter')->latest()->limit(1)->get();
        if($latest_form){
            $nomor = $latest_form->id + 1;
        }else{
            $nomor = 1;
        }
        $karyawan = Karyawan::where('id',$request->id_karyawan)->first();
        
        if($karyawan['status_karyawan'] == 'karyawan_tetap'){
            $status = 'KT';
        }else{
            $status = 'PS';
        }
        
        // dd($dokter[0]['name']);
        $request['nomor_surat']= $nomor .'/' .$status.'/450/SDM/'.date('Y');
        $request['status_email'] = 0;
        $request['status_pengajuan'] = 'Menunggu Persetujuan Dokter';
        $requestData = $request->all();
        $data_karyawan = FormJaminan::create($requestData);
        // if($data_karyawan){
        //     Mail::to($dokter[0]['email'])->send(new \App\Mail\Dokter_Mail($dokter,$data_karyawan));
        // }
        Alert::success('New ' . 'FormJaminan'. ' Created!' );
        $nomor++;
        if($karyawan['status_karyawan']=='karyawan_tetap'){
            return redirect('admin/form-jaminan');
        }else{
            return redirect('admin/form-jaminan/pensiunan');
        }
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
        $formjaminan = FormJaminan::findOrFail($id);

        return view('admin.form-jaminan.show', compact('formjaminan'));
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
        $formjaminan = FormJaminan::findOrFail($id);
        if($formjaminan['karyawan']['status_karyawan'] == 'karyawan_tetap'){
            $where = 'karyawan_tetap';
        }else{
            $where = 'pensiunan';
        }
        $karyawan = Karyawan::where('status_karyawan',$where)->get();
        // $instansi = Instansi::pluck('nama_instansi', 'id');
        $jenisPemeriksaan = JenisPemeriksaan::pluck('jenis_pemeriksaan', 'id');
        $rumahSakit = RumahSakit::pluck('nama_rumah_sakit', 'id');
        $data['formjaminan'] = $formjaminan;
        $data['karyawan'] = $karyawan;
        // $data['instansi'] = $instansi;
        $data['jenisPemeriksaan'] = $jenisPemeriksaan;
        $data['rumahSakit'] = $rumahSakit;
        $data['formjaminan'] = $formjaminan;
        return view('admin.form-jaminan.edit', $data);
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
        
        $requestData = $request->all();
        
        $formjaminan = FormJaminan::findOrFail($id);
        Alert::success('Record Updated!' );
        $formjaminan->update($requestData);

        return redirect('admin/form-jaminan');
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
        FormJaminan::destroy($id);

        return redirect('admin/form-jaminan');
    }
    public function getKelas($id) {
        $karyawan = Karyawan::find($id);
        $data['kelas'] = KelasRawatInap::find($karyawan->id_kelas_rawat_inap);

        return $data;
    }
    public function getPasien($id) {
        $data['pasien'] = Karyawan::find($id);
        return $data;
    }
    public function sendEmail($id)
    {
        $formjaminan = FormJaminan::where('id',$id)->first();
        // if($formjaminan['rumahsakit']['email']){
        //     Mail::to($formjaminan['rumahsakit']['email'])->send(new \App\Mail\FormJaminanEmail($formjaminan));

        // }
        // if($formjaminan['karyawan']['email']){
        //     Mail::to($formjaminan['karyawan']['email'])->send(new \App\Mail\Karyawan_Mail($formjaminan));
        // }
        Alert::success('Email Success Sent !' );
        $formjaminan->status_email = true;
        $formjaminan->save();
        if($formjaminan['karyawan']['status_karyawan'] == 'karyawan_tetap'){
            return redirect('admin/form-jaminan');
        }
        else{
            return redirect('admin/form-jaminan/pensiunan');
        }
    }

    public function generatePDF($id)
    {
        $formjaminan = FormJaminan::where('id',$id)->first();
        $mkad = User::where('role','mkad')->latest()->limit(1)->get();
        $sm = User::where('role','sm')->latest()->limit(1)->get();
        $slug = Str::slug($formjaminan['karyawan']['nama_karyawan'], '-');
        $nomor_pecah = explode("/",$formjaminan->nomor_surat);
        $nomor_gabung = implode("-",$nomor_pecah);
        $pdf_name = $nomor_gabung.'-'.$formjaminan->jenis_surat.'-'.date('Y-m-d').'-'.$formjaminan['karyawan']['nid'].'-'. $slug .'.pdf' ;
        $formjaminan->file_pdf = $pdf_name;
        $formjaminan->save();

        $data_pdf= PDF::loadView('admin/template/pdf', compact(['formjaminan','mkad','sm']))->setPaper('a4', 'portrait')->save(public_path('generate-pdf/'.$pdf_name));


        Alert::success('Generate PDF Success' );
        if($formjaminan['karyawan']['status_karyawan'] == 'karyawan_tetap'){
            return redirect('admin/form-jaminan');
        }
        else{
            return redirect('admin/form-jaminan/pensiunan');
        }
    }
    public function show_dashboard($id)
    {
        $formjaminan = FormJaminan::findOrFail($id);

        return view('admin.form-jaminan.show_dashboard', compact('formjaminan'));
    }
}
