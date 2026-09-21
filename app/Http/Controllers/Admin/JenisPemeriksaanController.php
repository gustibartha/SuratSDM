<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Alert;
use App\Models\JenisPemeriksaan;
use Illuminate\Http\Request;

class JenisPemeriksaanController extends Controller
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
        $jenispemeriksaan = JenisPemeriksaan::latest()->paginate($perPage);
        $data['jenispemeriksaan'] = $jenispemeriksaan;
        return view('admin.jenis-pemeriksaan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.jenis-pemeriksaan.create');
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
        
        JenisPemeriksaan::create($requestData);
        Alert::success('New ' . 'JenisPemeriksaan'. ' Created!' );

        return redirect('admin/jenis-pemeriksaan');
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
        $jenispemeriksaan = JenisPemeriksaan::findOrFail($id);

        return view('admin.jenis-pemeriksaan.show', compact('jenispemeriksaan'));
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
        $jenispemeriksaan = JenisPemeriksaan::findOrFail($id);
        $data['jenispemeriksaan'] = $jenispemeriksaan;
        return view('admin.jenis-pemeriksaan.edit', $data);
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
        
        $jenispemeriksaan = JenisPemeriksaan::findOrFail($id);
        Alert::success('Record Updated!' );
        $jenispemeriksaan->update($requestData);

        return redirect('admin/jenis-pemeriksaan');
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
        JenisPemeriksaan::destroy($id);

        return redirect('admin/jenis-pemeriksaan');
    }
}
