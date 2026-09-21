<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Alert;
use App\Models\KelasRawatInap;
use Illuminate\Http\Request;

class KelasRawatInapController extends Controller
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
        $kelasrawatinap = KelasRawatInap::paginate($perPage);
        $data['kelasrawatinap'] = $kelasrawatinap;
        return view('admin.kelas-rawat-inap.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.kelas-rawat-inap.create');
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
        
        KelasRawatInap::create($requestData);
        Alert::success('New ' . 'Kelas Rawat Inap'. ' Created!' );

        return redirect('admin/kelas-rawat-inap');
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
        $kelasrawatinap = KelasRawatInap::findOrFail($id);

        return view('admin.kelas-rawat-inap.show', compact('kelasrawatinap'));
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
        $kelasrawatinap = KelasRawatInap::findOrFail($id);
        $data['kelasrawatinap'] = $kelasrawatinap;
        return view('admin.kelas-rawat-inap.edit', $data);
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
        
        $kelasrawatinap = KelasRawatInap::findOrFail($id);
        Alert::success('Record Updated!' );
        $kelasrawatinap->update($requestData);

        return redirect('admin/kelas-rawat-inap');
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
        KelasRawatInap::destroy($id);

        return redirect('admin/kelas-rawat-inap');
    }
}
