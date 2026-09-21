<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Alert;
use App\Models\MonitoringTagihan;
use App\Models\FormJaminan;
use Illuminate\Http\Request;

class MonitoringTagihanController extends Controller
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
        $monitoringtagihan = MonitoringTagihan::latest()->get();

        $data['formjaminan'] = FormJaminan::where('status_pengajuan','Sudah Disetujui MKAD')
        ->pluck('nomor_surat','id');
        $data['monitoringtagihan'] = $monitoringtagihan;
        return view('admin.monitoring-tagihan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.monitoring-tagihan.create');
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
        MonitoringTagihan::create($requestData);
        Alert::success('New ' . 'MonitoringTagihan'. ' Created!' );
        return redirect('admin/monitoring-tagihan');

     
     
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
        $monitoringtagihan = MonitoringTagihan::findOrFail($id);

        return view('admin.monitoring-tagihan.show', compact('monitoringtagihan'));
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
        $monitoringtagihan = MonitoringTagihan::findOrFail($id);
        $exist_monitoring = MonitoringTagihan::latest()->get();
        foreach($exist_monitoring as $i => $item) {
            $existing_id_form[] = $item->id_form_jaminan;
        }
        $data['formjaminan'] = FormJaminan::where('status_pengajuan','Sudah Disetujui MKAD')
        ->whereNotIn('id', $existing_id_form)
        ->pluck('nomor_surat','id');
        $data['exist_monitoring'] = $exist_monitoring;
        $data['monitoringtagihan'] = $monitoringtagihan;
        return view('admin.monitoring-tagihan.edit', $data);
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
        
        $monitoringtagihan = MonitoringTagihan::findOrFail($id);
        Alert::success('Record Updated!' );
        $monitoringtagihan->update($requestData);

        return redirect('admin/monitoring-tagihan');
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
        MonitoringTagihan::destroy($id);

        return redirect('admin/monitoring-tagihan');
    }

    public function showMonitoring($id){
     
        $monitoringtagihan = MonitoringTagihan::findOrFail($id);

        return view('admin.monitoring-tagihan.show-detail', compact('monitoringtagihan'));
    
    }
}
