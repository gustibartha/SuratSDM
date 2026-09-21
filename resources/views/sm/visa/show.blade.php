@extends('layouts.backend-sm')
@section('styles')

<style>
     td ul li{
        list-style-type: disc !important;
        color:black;
    }
</style>
    
@endsection
@section('content')
<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
                <aside class="sidebar sidebar-user">
                    <div class="card ctm-border-radius shadow-sm">
                        <div class="card-body py-4">
                            <div class="row">
                                <div class="col-md-12 mr-auto text-left">
                                    <div class="custom-search input-group">
                                        <div class="custom-breadcrumb">
                                            <ol class="breadcrumb no-bg-color d-inline-block p-0 m-0 mb-2">
                                                <li class="breadcrumb-item d-inline-block"><a
                                                        href="{{ url('/sm') }}" class="text-dark">Home</a></li>
                                                <li class="breadcrumb-item d-inline-block active">Visa</li>
                                            </ol>
                                            <h4 class="text-dark">Visa Detail</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>

            <div class="col-xl-9 col-lg-8  col-md-12">
                <div class="card shadow-sm ctm-border-radius">
                    <div class="card-body align-center">
                        <h4 class="card-title float-left mb-0 mt-2">Visa Detail</h4>
                        <ul class="nav nav-tabs float-right border-0 tab-list-emp">
                        </ul>
                    </div>
                </div>
                <div class="ctm-border-radius shadow-sm card">
                    <div class="card-body">

                        <!--Content table-->
                        <div class="table-back employee-office-table">
                            <div class="table-responsive">
                                <table class="table custom-table table-hover table-hover">
                                    <tbody>
                                        <tr>
                                            <th> Nomor Surat </th>
                                            <td> {{ $record->nomor_surat }} </td>
                                        </tr>
                                        <tr>
                                            <th> Nama Karyawan </th>
                                            <td> {{ $record->karyawan->nama_karyawan }} </td>
                                        </tr>
                                        <tr>
                                            <th> Nid </th>
                                            <td> {{ $record->karyawan->nid }} </td>
                                        </tr>
                                        <tr>
                                            <th> Jenis </th>
                                            <td> {{ $record->jenis }} </td>
                                        </tr>
                                        <tr>
                                            <th> Tujuan </th>
                                            <td> {{ $record->tujuan }} </td>
                                        </tr>
                                        <tr>
                                            <th> Alamat </th>
                                            <td> {{ $record->alamat }} </td>
                                        </tr>
                                        <tr>
                                            <th> Tanggal Mulai </th>
                                            <td> {{ $record->tanggal_mulai }} </td>
                                        </tr>
                                        <tr>
                                            <th> Tanggal Selesai </th>
                                            <td> {{ $record->tanggal_selesai }} </td>
                                        </tr>
                                        <tr>
                                            <th> Negara </th>
                                            <td> {{ $record->negara_tujuan }} </td>
                                        </tr>
                                        <tr>
                                            <th> Keperluan </th>
                                            <td> {{ $record->keperluan }} </td>
                                        </tr>
                                        <tr>
                                            <th> Keluarga </th>
                                            <td class="w-50" style="width: 50% !important">
                                                <ul type="1">
                                                    @foreach ($keluargas as $keluarga)
                                                        <li> {{$keluarga->nama }} - {{$keluarga->hubungan}} - {{$keluarga->nomor_passport}}</li>
                                                  
                                                    @endforeach
                                                </ul>
                                               
                                                
                                            </td>
                                        </tr>
                                    </tbody>

                                        <tr>
                                            <td colspan="10" align="center">
                                                <a href="{{ url('/sm') }}"><button
                                                    class="btn btn-sm btn-warning"><span
                                                        class="lnr lnr-arrow-left"> Back</span>
                                                    </button>
                                            </a>
                                            @if ($record->rangking == 3)
                                                @if ($record->is_rejected == false)
                                                    
                                                    <a href="{{ url('/sm/visa/approve/' . $record->id) }}"><button
                                                        class="btn btn-sm btn-success"><span
                                                        class="lnr lnr-checkmark-circle"> Approve</span>
                                                        </button>
                                                    </a>
                                                    <a href="{{ url('/sm/visa/reject/' . $record->id) }}"><button
                                                        class="btn btn-sm btn-danger"><span
                                                        class="lnr lnr-cross-circle"> Reject</span>
                                                        </button>
                                                    </a>
                                                @endif
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /Content Table -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <!--/Content-->
@endsection
