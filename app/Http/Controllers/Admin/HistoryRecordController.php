<?php

namespace App\Http\Controllers\Admin;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use App\Models\HistoryRecord;
use App\Http\Controllers\Controller;
use Alert;

class HistoryRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['records'] = HistoryRecord::latest()->get();
        $data['karyawans'] = Karyawan::all();
        return view('admin.history-record.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.history-record.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestData = $request->all();
        
        HistoryRecord::create($requestData);
        Alert::success('New ' . 'History Record'. ' Created!' );

        return redirect('admin/history-record');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\HistoryRecord  $historyRecord
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $record = HistoryRecord::findOrFail($id);
        return view('admin.history-record.show', compact('record'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\HistoryRecord  $historyRecord
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['record'] = HistoryRecord::findOrFail($id);
        $data['karyawans'] = Karyawan::all();
        return view('admin.history-record.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\HistoryRecord  $historyRecord
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $requestData = $request->all();
        
        $record = HistoryRecord::findOrFail($id);
        Alert::success('Record Updated!' );
        $record->update($requestData);

        return redirect('admin/history-record');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\HistoryRecord  $historyRecord
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Alert::success('Record Deleted!' );
        HistoryRecord::destroy($id);

        return redirect('admin/history-record');
    }
}
