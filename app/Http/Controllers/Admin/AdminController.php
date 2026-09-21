<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Models\Visa;
use App\Models\FormJaminan;
use Illuminate\Http\Request;
use App\Models\SuratKeterangan;
use App\Models\MonitoringTagihan;
use App\Http\Controllers\Controller;


class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {   
        $formjaminan['monitoring'] =MonitoringTagihan::latest()->limit(3)->get();
        $formjaminan['sudah'] =FormJaminan::latest()->limit(3)->get();
        $formjaminan['count_monitoring'] = MonitoringTagihan::count();
        $formjaminan['count_sudah'] = FormJaminan::count();
        $formjaminan['keterangan'] =SuratKeterangan::latest()->limit(3)->get();
        $formjaminan['visa'] =Visa::latest()->limit(3)->get();
        $formjaminan['count_keterangan'] = SuratKeterangan::count();
        $formjaminan['count_visa'] = Visa::count();
    
        return view('admin.dashboard', $formjaminan);
    }

}
