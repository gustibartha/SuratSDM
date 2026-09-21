@extends('layouts.backend-dokter')

@section('content')
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-2 col-lg-4 col-md-12 theiaStickySidebar">
                    <aside class="sidebar sidebar-user">
                        <div class="card ctm-border-radius shadow-sm">
                            <div class="card-body py-4">
                                <div class="row">
                                    <div class="col-md-12 mr-auto text-left">
                                        <div class="custom-search input-group">
                                            <div class="custom-breadcrumb">
                                                <ol class="breadcrumb no-bg-color d-inline-block p-0 m-0 mb-2">
                                                    <li class="breadcrumb-item d-inline-block"><a
                                                            href="{{ url('/dokter') }}" class="text-dark">Home</a></li>
                                                    <li class="breadcrumb-item d-inline-block active">Form Jaminan</li>
                                                </ol>
                                                <h4 class="text-dark">Form Jaminan Pensiunan</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="quicklink-sidebar-menu ctm-border-radius shadow-sm bg-white card">
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item text-center button-1"><a
                                            href="{{ url('/dokter/form-jaminan') }}" class="text-white">Karyawan</a>
                                    </li>
                                    <li class="list-group-item text-center button-1"><a
                                            href="{{ url('/dokter/form-jaminan-pensiunan') }}"
                                            class="text-white">Pensiunan</a></li>
                                </ul>
                            </div>
                        </div>
                    </aside>
                </div>

                <div class="col-xl-10 col-lg-8  col-md-12">
                    <div class="card shadow-sm ctm-border-radius">
                        <div class="card-body align-center">
                            <div class="card-body align-center">
                                <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                    <li class="nav-item mr-md-1">
                                        <a class="nav-link active" id="pills-personal-tab" data-toggle="pill"
                                            href="#pills-personal" role="tab" aria-controls="pills-home"
                                            aria-selected="true">Personal</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-keluarga-tab" data-toggle="pill"
                                            href="#pills-keluarga" role="tab" aria-controls="pills-keluarga"
                                            aria-selected="false">Keluarga</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="ctm-border-radius shadow-sm card">
                            <div class="card-body">
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-personal" role="tabpanel"
                                        aria-labelledby="pills-personal-tab">
                                        <!--Content table-->
                                        <div class="table-back employee-office-table">
                                            <div class="table-responsive">
                                                <table id="data-table-1" class="table custom-table table-hover table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            {{-- <th>Nomor Surat</th>
                                                            <th>Jenis Surat</th> --}}
                                                            {{-- <th>Nama Karyawan</th>
                                                            <th>NID</th> --}}
                                                            <th>Detail</th>
                                                            {{-- <th>Jenis Pemeriksaan</th>
                                                            <th>Rumah Sakit</th>
                                                            <th>Kelas Rawat Inap</th> --}}
                                                            <th>Status Pengajuan</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($formjaminan_personal as $i => $item)
                                                            <tr class="{{$item->rangking == 1 ? 'bg-danger' :''}}">
                                                                <td class="{{$item->rangking == 1 ? 'text-white' :''}}">{{ $i + 1 }}</td>
                                                                {{-- <td class="{{$item->rangking == 1 ? 'text-white' :''}}">{{ $item->nomor_surat }}</td>
                                                                <td class="{{$item->rangking == 1 ? 'text-white' :''}}">{{ $item->jenis_surat }}</td> --}}
                                                                {{-- <td class="{{$item->rangking == 1 ? 'text-white' :''}}">{{ $item->karyawan['nama_karyawan'] }}</td>
                                                                <td class="{{$item->rangking == 1 ? 'text-white' :''}}">{{ $item->karyawan['nid'] }}</td> --}}
                                                                <td class="{{$item->rangking == 1 ? 'text-white' :''}}">
                                                                    <span>Nama Karyawan : {{ $item->karyawan['nama_karyawan'] ?? '' }}</span>
                                                                    <br>
                                                                    <span>NID : {{ $item->karyawan['nid'] ?? '' }}</span>
                                                                    <br>
                                                                    <span>Nomor Surat : {{ $item->nomor_surat }}</span>
                                                                    <br>
                                                                    <span>Jenis Pemeriksaan : {{ $item->jenisPemeriksaan['jenis_pemeriksaan'] ?? '' }} </span>
                                                                    <br>
                                                                    <span>Rumah Sakit : {{ $item->rumahSakit['nama_rumah_sakit'] ?? '' }}</span>
                                                                    <br>
                                                                    <span>Kelas Rawat Inap : {{ $item->jenis_kelas }} /
                                                                        {{ number_format($item->harga) }}</span>
                                                                </td>
                                                                {{-- <td class="{{$item->rangking == 1 ? 'text-white' :''}}">{{ $item->jenisPemeriksaan['jenis_pemeriksaan'] }}
                                                                </td>
                                                                <td class="{{$item->rangking == 1 ? 'text-white' :''}}">{{ $item->rumahSakit['nama_rumah_sakit'] }}</td>
                                                                <td class="{{$item->rangking == 1 ? 'text-white' :''}}">{{ $item->jenis_kelas }} /
                                                                    {{ number_format($item->harga) }}</td> --}}
                                                                <td class="{{$item->rangking == 1 ? 'text-white' :''}}">{{ $item->status_pengajuan }}</td>
                                                                <td class="text-right" align="center">
                                                                    <div class="table-action">
                                                                        <a href="{{ url('/dokter/form-jaminan/' . $item->id) }}"
                                                                            title="View FormJaminan"  target="_blank">
                                                                            <button class="btn btn-sm btn-info">
                                                                                <span class="lnr lnr-eye"></span>View
                                                                            </button>
                                                                        </a>
                                                                
                                                                        <a href="{{ url('/dokter/show-pdf/' . $item->id) }}"
                                                                            title="View FormJaminan" target="_blank">
                                                                            <button class="btn btn-sm btn-warning text-white">
                                                                                <span class="lnr lnr-eye"></span>Preview PDF
                                                                            </button>
                                                                        </a>

                                                                        @if($item->rangking == 1 )
                                                                        <a href="{{ url('/dokter/form-jaminan/approve/' . $item->id) }}"
                                                                            title="Approve FormJaminan">
                                                                            <button class="btn btn-sm btn-success">
                                                                                <span class="fa fa-check"></span>Approve
                                                                            </button>
                                                                        </a>
                                                                        @endif
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
                                    <div class="tab-pane fade" id="pills-keluarga" role="tabpanel"
                                        aria-labelledby="pills-keluarga-tab">
                                        <!--Content table-->
                                        <div class="table-back employee-office-table">
                                            <div class="table-responsive">
                                                <table id="data-table-2" class="table custom-table table-hover table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            {{-- <th>Nomor Surat</th>
                                                            <th>Jenis Surat</th> --}}
                                                            {{-- <th>Nama Karyawan</th>
                                                            <th>NID</th> --}}
                                                            <th>Detail</th>
                                                            {{-- <th>Nama Pasien</th>
                                                            <th>Jenis Pemeriksaan</th>
                                                            <th>Rumah Sakit</th>
                                                            <th>Kelas Rawat Inap</th> --}}
                                                            <th>Status Pengajuan</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($formjaminan_keluarga as $i => $item)
                                                            <tr class="{{$item->rangking == 1 ? 'bg-danger' :''}}">
                                                                <td class="{{$item->rangking == 1 ? 'text-white' :''}}">{{ $i + 1 }}</td>
                                                                {{-- <td class="{{$item->rangking == 1 ? 'text-white' :''}}">{{ $item->nomor_surat }}</td>
                                                                <td class="{{$item->rangking == 1 ? 'text-white' :''}}">{{ $item->jenis_surat }}</td> --}}
                                                                {{-- <td class="{{$item->rangking == 1 ? 'text-white' :''}}">{{ $item->karyawan['nama_karyawan'] }}</td>
                                                                <td class="{{$item->rangking == 1 ? 'text-white' :''}}">{{ $item->karyawan['nid'] }}</td> --}}
                                                                <td class="{{$item->rangking == 1 ? 'text-white' :''}}">
                                                                    <span>Nama Karyawan : {{ $item->karyawan['nama_karyawan'] ?? '' }}</span>
                                                                    <br>
                                                                    <span>NID : {{ $item->karyawan['nid'] ?? '' }}</span>
                                                                    <br>
                                                                    <span>Nomor Surat : {{ $item->nomor_surat }}</span>
                                                                    <br>
                                                                    <span>Nama Pasien : {{ $item->nama_pasien }}</span>
                                                                    <br>
                                                                    <span>Jenis Pemeriksaan : {{ $item->jenisPemeriksaan['jenis_pemeriksaan'] ?? '' }} </span>
                                                                    <br>
                                                                    <span>Rumah Sakit : {{ $item->rumahSakit['nama_rumah_sakit'] ?? '' }}</span>
                                                                    <br>
                                                                    <span>Kelas Rawat Inap : {{ $item->jenis_kelas }} /
                                                                        {{ number_format($item->harga) }}</span>
                                                                </td>
                                                                {{-- <td class="{{$item->rangking == 1 ? 'text-white' :''}}">{{ $item->nama_pasien }}</td>
                                                                <td class="{{$item->rangking == 1 ? 'text-white' :''}}">{{ $item->jenisPemeriksaan['jenis_pemeriksaan'] }}
                                                                </td>
                                                                <td class="{{$item->rangking == 1 ? 'text-white' :''}}">{{ $item->rumahSakit['nama_rumah_sakit'] }}</td>
                                                                <td class="{{$item->rangking == 1 ? 'text-white' :''}}">{{ $item->jenis_kelas }} /
                                                                    {{ number_format($item->harga) }}</td> --}}
                                                                <td class="{{$item->rangking == 1 ? 'text-white' :''}}">{{ $item->status_pengajuan }}</td>
                                                                <td class="text-right" align="center">
                                                                    <div class="table-action">
                                                                        <a href="{{ url('/dokter/form-jaminan/' . $item->id) }}"
                                                                            title="View FormJaminan"  target="_blank">
                                                                            <button class="btn btn-sm btn-info">
                                                                                <span class="lnr lnr-eye"></span>View
                                                                            </button>
                                                                        </a>
                                                                           
                                                                       <a href="{{ url('/dokter/show-pdf/' . $item->id) }}"
                                                                        title="View FormJaminan"  target="_blank">
                                                                        <button class="btn btn-sm btn-warning text-white">
                                                                            <span class="lnr lnr-eye"></span>Preview PDF
                                                                        </button>
                                                                        </a>
                                                                        @if($item->rangking == 1 )
                                                                        <a href="{{ url('/dokter/form-jaminan/approve/' . $item->id) }}"
                                                                            title="Approve FormJaminan">
                                                                            <button class="btn btn-sm btn-success">
                                                                                <span class="fa fa-check"></span>Approve
                                                                            </button>
                                                                        </a>
                                                                        @endif
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
        </div>
        <!--/Content-->
    @endsection
