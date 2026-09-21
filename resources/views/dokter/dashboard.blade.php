@extends('layouts.backend-dokter')

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
                                                    <li class="breadcrumb-item d-inline-block"><a href="index.html"
                                                            class="text-dark">Home</a></li>
                                                    <li class="breadcrumb-item d-inline-block active">Dashboard</li>
                                                </ol>
                                                <h4 class="text-dark">Dokter Dashboard</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="user-card card shadow-sm bg-white text-center ctm-border-radius">
                            <div class="user-info card-body">
                                <div class="user-avatar mb-4">
                                    <img src="{{ asset('vendor/lakers') }}/img/logo-pjb.png" alt="User Avatar"
                                        class="img-fluid rounded-circle" width="100">
                                </div>
                                <div class="user-details">
                                    <h4 class="text-uppercase"><b>Selamat Datang {{ Auth::user()->name }}</b></h4>
                                    <h6 class="mt-3"><span class="p-1">PT PLN Nusantara Power <br> Unit Pembangkitan Muara Karang</span>
                                    </h6>
                                    <h6 class="mt-3">
                                        {{ date('D, F j Y') }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>

                <div class="col-xl-9 col-lg-8 col-md-12">

                    <!-- Widget -->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card dash-widget ctm-border-radius shadow-sm">
                                <div class="card-body">
                                    <div class="card-icon bg-warning">
                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                    </div>
                                    <div class="card-right mx-auto text-center">
                                        <h4 class="card-title">SURAT JAMINAN YANG SUDAH DI APPROVE</h4>
                                        <p class="card-text">{{ $count_sudah }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card dash-widget ctm-border-radius shadow-sm">
                                <div class="card-body">
                                    <div class="card-icon bg-success">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                    <div class="card-right mx-auto text-center">
                                        <h4 class="card-title">MENUNGGU PERSETUJUAN DOKTER</h4>
                                        <p class="card-text">{{ $count_menunggu }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <!-- Widget -->
                        {{-- sudah dibuat --}}
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-right">
                                            <h4 class="card-title mr-auto mx-auto">SURAT JAMINAN YANG SUDAH
                                                DIAPPROVE
                                            </h4>
                                            <div class="border bg-primary div-sm-1 text-light p-2 text-center w-25 mx-auto">{{ $count_sudah }}</div>
                                        </div>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        @foreach ($sudah as $item)

                                            <li class="list-group-item">
                                                <div class="card-body">
                                                    <div class="card-icon bg-warning">
                                                        <i class="fa fa-envelope"></i>
                                                    </div>
                                                    <div class="card-right"  style="font-size: 10px;">
                                                        <a href="{{ url('form-jaminans/' . $item->id) }}"
                                                            class="card-title font-weight-bold">{{ $item->nomor_surat ?? '' }}
                                                        </a>
                                                        <div class="text">{{ $item->karyawan['nama_karyawan'] ??'' }} </div>
                                                        <div class="border bg-primary p-sm-1 font-weight-bold text-white col-md-6 text-center" style="font-size:12px;">DISETUJUI</div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            {{-- menunggu persetujuan --}}
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-right">
                                            <h4 class="card-title mr-auto mx-auto">MENUNGGU PERSETUJUAN DOKTER
                                            </h4>
                                            <div class="border bg-success div-sm-1 text-light p-2 text-center w-25 mx-auto">{{ $count_menunggu }}</div>
                                            
                                        </div>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        @foreach ($menunggu as $item)
                                            <li class="list-group-item">
                                                <div class="card-body">
                                                    <div class="card-icon bg-success">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                    <div class="card-right"  style="font-size: 10px;">
                                                        <a href="{{ url('form-jaminans/' . $item->id) }}"
                                                            class="card-title font-weight-bold text-primary">{{ $item->nomor_surat ?? '' }}
                                                        </a>
                                                        <div class="text">{{ $item->karyawan['nama_karyawan'] ??'' }} </div>
                                                        <div class="border bg-danger p-sm-1 font-weight-bold text-white col-md-6 text-center" style="font-size:12px;"> BELUM DISETUJUI</div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- / Widget -->
                    </div>
                </div>
            </div>
        </div>
        <!--/Content-->
    @endsection
