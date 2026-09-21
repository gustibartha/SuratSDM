<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Alert;
use App\Models\Karyawan;
use App\Models\KelasRawatInap;
use Illuminate\Http\Request;

class KaryawanController extends Controller
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
        $karyawan_tetap = Karyawan::where('status_karyawan','karyawan_tetap')->with('kelasRawatInap')->get();
        $pensiunan = Karyawan::where('status_karyawan','pensiunan')->with('kelasRawatInap')->get();
        $data['kelasRawatInap'] = KelasRawatInap::pluck('jenis_kelas','id');
        $data['karyawan'] = $karyawan_tetap;
        $data['pensiunan'] = $pensiunan;
        return view('admin.karyawan.index', $data);
        // return $data['karyawan'];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.karyawan.create');
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
        
        $requestData = $request->all();
        
        Karyawan::create($requestData);
        Alert::success('New ' . 'Karyawan'. ' Created!' );

        return redirect('admin/karyawan');
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
        $karyawan = Karyawan::findOrFail($id);

        return view('admin.karyawan.show', compact('karyawan'));
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
        $karyawan = Karyawan::findOrFail($id);
        $data['kelasRawatInap'] = KelasRawatInap::pluck('jenis_kelas','id');
        $data['karyawan'] = $karyawan;
        return view('admin.karyawan.edit', $data);
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
        
        $karyawan = Karyawan::findOrFail($id);
        Alert::success('Record Updated!' );
        $karyawan->update($requestData);

        return redirect('admin/karyawan');
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
        Karyawan::destroy($id);

        return redirect('admin/karyawan');
    }
}
