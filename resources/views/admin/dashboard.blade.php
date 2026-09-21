@extends('layouts.backend')

@section('content')
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                {{-- <div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
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
                                                <h4 class="text-dark">Admin Dashboard</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="user-card card shadow-sm bg-white text-center ctm-border-radius">
                            <div class="user-info card-body">
                                <div class="user-avatar mb-4">
                                    <img src="{{asset('vendor/lakers/img/logo-pjb.png')}}" alt="User Avatar"
                                        class="img-fluid" width="300">
                                </div>
                                <div class="user-details">
                                    <h4 class="text-uppercase"><b>Selamat Datang {{ Auth::user()->name }}</b></h4>
                                    <h6 class="mt-3"><span class="">PT PLN Nusantara Power <br> Unit Pembangkitan Muara Karang</span>
                                    </h6>
                                    <h6 class="mt-3">
                                        {{ date('D, F j Y') }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div> --}}

                <div class="col-xl-12 col-lg-12 col-md-12">

                    <!-- Widget -->
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="card dash-widget ctm-border-radius shadow-sm">
                                <div class="card-body">
                                    <div class="card-icon bg-warning">
                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                    </div>
                                    <div class="card-right">
                                        <h4 class="card-title">SURAT JAMINAN YANG SUDAH DI BUAT</h4>
                                        <p class="card-text">{{ $count_sudah }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card dash-widget ctm-border-radius shadow-sm">
                                <div class="card-body">
                                    <div class="card-icon bg-secondary">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                    <div class="card-right">
                                        <h4 class="card-title">MONITORING TAGIHAN YANG  DI BUAT</h4>
                                        <p class="card-text">{{ $count_monitoring }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card dash-widget ctm-border-radius shadow-sm">
                                <div class="card-body">
                                    <div class="card-icon bg-danger">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <div class="card-right">
                                        <h4 class="card-title">SURAT KETERANGAN</h4>
                                        <p class="card-text">{{ $count_keterangan }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card dash-widget ctm-border-radius shadow-sm">
                                <div class="card-body">
                                    <div class="card-icon bg-primary">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <div class="card-right">
                                        <h4 class="card-title">SURAT VISA</h4>
                                        <p class="card-text">{{ $count_visa }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <!-- Widget -->
                        {{-- sudah dibuat --}}
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-right">
                                            <h4 class="card-title mr-auto float-right">DAFTAR SURAT JAMINAN SUDAH
                                                DI BUAT <span class="border bg-primary p-sm-1 text-white">{{ $count_sudah }}</span>
                                            </h4>
                                        </div>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        @foreach ($sudah as $item)

                                            <li class="list-group-item">
                                                <div class="card-body">
                                                    <div class="card-icon bg-warning">
                                                        <i class="fa fa-envelope"></i>
                                                    </div>
                                                    <div class="card-right">
                                                        {{-- <h4 class="card-title font-weight-bold">{{ $item->nomor_surat }}
                                                        </h4> --}}
                                                        <a href="{{ url('form-jaminans/' . $item->id) }}"
                                                            class="card-title font-weight-bold">{{ $item->nomor_surat }}
                                                        </a>
                                                        <span>{{ $item->karyawan['nama_karyawan'] }} <span
                                                                class="border bg-primary font-weight-bold" style="font-size: 10px;color:white;">{{$item->status_pengajuan}}</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            {{-- menunggu persetujuan --}}
                            <div class="col-sm-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-right">
                                            <h4 class="card-title mr-auto float-right">MONITORING TAGIHAN YANG SUDAH DI BUAT <span
                                                    class="border bg-success text-light">{{ $count_monitoring }}</span>
                                            </h4>
                                        </div>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        @foreach ($monitoring as $item)
                                            <li class="list-group-item">
                                                <div class="card-body">
                                                    <div class="card-icon bg-secondary">
                                                        <i class="fa fa-clock-o"></i>
                                                    </div>
                                                    <div class="card-right">
                                                        {{-- <h4 class="card-title font-weight-bold">{{ $item->nomor_surat }} --}}
                                                        {{-- </h4> --}}
                                                        <a href="{{ url('admin/detail-monitoring/' . $item->id) }}"
                                                            class="card-title font-weight-bold">{{ $item->formJaminan->nomor_surat ?? '' }}
                                                        </a>
                                                        <span>{{ $item->formJaminan->karyawan->nama_karyawan ?? '' }} <span
                                                                class="border bg-success p-sm-1 font-weight-bold text-light" style="font-size: 12px;">{{$item->status_pembayaran}}</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-right">
                                            <h4 class="card-title mr-auto float-right">SURAT KETERANGAN <span
                                                    class="border bg-success p-sm-1 text-light">{{ $count_keterangan }}</span>
                                            </h4>
                                        </div>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        @foreach ($keterangan as $item)
                                            <li class="list-group-item">
                                                <div class="card-body">
                                                    <div class="card-icon bg-danger">
                                                        <i class="fa fa-envelope"></i>
                                                    </div>
                                                    <div class="card-right">
                                                        {{-- <h4 class="card-title font-weight-bold">{{ $item->nomor_surat }} --}}
                                                        {{-- </h4> --}}
                                                        <a href="#" style="pointer-events: none"
                                                            class="card-title font-weight-bold">{{ $item->nomor_surat ?? '' }}
                                                        </a>
                                                        <span>{{ $item->karyawan->nama_karyawan ?? '' }} <span
                                                                class="border bg-success font-weight-bold text-light" style="font-size: 10px;">{{$item->status}}</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-right">
                                            <h4 class="card-title mr-auto float-right">SURAT VISA <span
                                                    class="border bg-success p-sm-1 text-light">{{ $count_visa }}</span>
                                            </h4>
                                        </div>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        @foreach ($visa as $item)
                                            <li class="list-group-item">
                                                <div class="card-body">
                                                    <div class="card-icon bg-primary">
                                                        <i class="fa fa-envelope"></i>
                                                    </div>
                                                    <div class="card-right">
                                                        {{-- <h4 class="card-title font-weight-bold">{{ $item->nomor_surat }} --}}
                                                        {{-- </h4> --}}
                                                        <a href="#" style="pointer-events: none"
                                                            class="card-title font-weight-bold">{{ $item->nomor_surat ?? '' }}
                                                        </a>
                                                        <span>{{ $item->karyawan->nama_karyawan ?? '' }} <span
                                                                class="border bg-success p-sm-1 font-weight-bold text-light" style="font-size: 12px;">{{$item->status}}</span>
                                                        </span>
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
