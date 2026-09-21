@extends('layouts.backend-sm')
@section('styles')
    <style>
        .text-ck ul li{
            list-style:disc !important;
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
                                                <h4 class="text-dark">Visa</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="quicklink-sidebar-menu ctm-border-radius shadow-sm bg-white card">
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item text-center button-1"><a href="{{ url('/sm/surat-keterangan') }}"
                                            class="text-white">Surat Keterangan</a></li>
                                    <li class="list-group-item text-center button-1"><a
                                            href="#" class="text-white">Visa</a></li>
                                </ul>
                            </div>
                        </div>
                    </aside>
                </div>

                <div class="col-xl-9 col-lg-8  col-md-12">
                    <div class="ctm-border-radius shadow-sm card">
                        <div class="card-body">
                            <!--Content table-->
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-karyawan" role="tabpanel"
                                    aria-labelledby="pills-karyawan-tab">
                                    <!--Content table-->
                                    <li class="nav-item float-right">
                                        {{-- <a href="{{'visa/create'}}" class="btn btn-theme text-white ctm-border-radius button-1"> <i
                                            class="fa fa-plus mx-2"></i> Tambah Data</a> --}}
                                    </li><br><br>
                                    <div class="table-back employee-office-table">
                                        <div class="table-responsive">
                                            <table id="data-table-1" class="table custom-table table-hover table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Karyawan</th>
                                                        <th>NID</th>
                                                        <th>Jenis Surat</th>
                                                        <th>Negara Tujuan</th>
                                                        <th>Keperluan</th>
                                                        <th>Tujuan</th>
                                                        <th>Alamat</th>
                                                        <th>Tanggal</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($visas as $i => $item)
                                                        <tr class={{$item->rangking == 3 ? 'bg-danger' :''}}>
                                                            <td class={{$item->rangking == 3 ? 'text-white' :''}}>{{ $i + 1  }}</td>
                                                            <td class={{$item->rangking == 3 ? 'text-white' :''}}>{{ $item->karyawan->nama_karyawan ?? '' }}</td>
                                                            <td class={{$item->rangking == 3 ? 'text-white' :''}}>{{ $item->karyawan->nid ?? '' }}</td>
                                                            <td class="text-ck {{$item->rangking == 3 ? 'text-white' :''}}">{{ $item->jenis }}</td>
                                                            <td class="text-ck {{$item->rangking == 3 ? 'text-white' :''}}">{{ $item->negara_tujuan }}</td>
                                                            <td class="text-ck {{$item->rangking == 3 ? 'text-white' :''}}">{{ $item->keperluan }}</td>
                                                            <td class="text-ck {{$item->rangking == 3 ? 'text-white' :''}}">{{ $item->tujuan }}</td>
                                                            <td class="text-ck {{$item->rangking == 3 ? 'text-white' :''}}">{{ $item->alamat }}</td>
                                                            <td class="text-ck {{$item->rangking == 3 ? 'text-white' :''}}">{{ $item->tanggal_mulai  }} s/d {{$item->tanggal_selesai}}</td>
                                                            <td class="text-ck {{$item->rangking == 3 ? 'text-white' :''}}">{{ $item->status  }}</td>

                                                            <td class="text-right" align="center">
                                                                <div class="table-action">
                                                                    <a href="{{ url('/sm/visa/pdf/' . $item->id) }}"
                                                                        title="View visa"><button
                                                                            class="btn btn-sm btn-info"><span
                                                                                class="lnr lnr-eye"></span>PDF</button></a>
                                                                        @if ($item->rangking == 3)
                                                                        <a href="{{ url('/sm/visa/approve/' . $item->id ) }}"
                                                                            
                                                                            title="Edit visa"><button
                                                                            class="btn btn-sm btn-success"><span
                                                                            class="lnr lnr-eye"></span>Approve</button></a>
                                                                            @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
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
    </div>
@endsection
