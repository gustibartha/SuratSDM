@extends('layouts.backend')

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
                                                            href="{{ url('/admin') }}" class="text-dark">Home</a></li>
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
                                            href="{{ url('/admin/form-jaminan') }}" class="text-white">Karyawan</a>
                                    </li>
                                    <li class="list-group-item text-center button-1"><a
                                            href="{{ url('/admin/form-jaminan/pensiunan') }}"
                                            class="text-white">Pensiunan</a></li>
                                </ul>
                            </div>
                        </div>
                    </aside>
                </div>

                <div class="col-xl-9 col-lg-8  col-md-12">
                    <div class="card shadow-sm ctm-border-radius">
                        {{-- <div class="card-body align-center">
                            <div class="card-icon bg-warning">
                                <i class="fa fa-envelope" aria-hidden="true"></i>
                            </div>
                            <h6 class="card-title float-left mb-0 mt-4 ml-2">TOTAL FORM JAMINAN PERAWATAN KESEHATAN DIGITAL
                                YANG
                                SUDAH DI BUAT : <span> {{ $formjaminan->count() }}</span></h6>
                            <ul class="nav nav-tabs float-right border-0 tab-list-emp">
                                <li class="nav-item pl-3 mt-2">
                                    <button class="btn btn-theme text-white ctm-border-radius button-1" data-toggle="modal"
                                        data-target="#add-FormJaminan"> <i class="fa fa-plus mx-2"></i>Tambah Form</button>
                                </li>
                            </ul>

                        </div> --}}
                        <div class="card-body align-center">
                            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                <li class="nav-item mr-md-1">
                                    <a class="nav-link active" id="pills-personal-tab" data-toggle="pill"
                                        href="#pills-personal" role="tab" aria-controls="pills-home"
                                        aria-selected="true">Personal</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-keluarga-tab" data-toggle="pill" href="#pills-keluarga"
                                        role="tab" aria-controls="pills-keluarga" aria-selected="false">Keluarga</a>
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
                                    <li class="nav-item float-right">
                                        <button class="btn btn-theme text-white ctm-border-radius button-1"
                                            data-toggle="modal" data-target="#add-FormJaminanPersonal"> <i
                                                class="fa fa-plus mx-2"></i>Buat Surat Jaminan</button>
                                    </li><br><br>
                                    <div class="table-back employee-office-table">
                                        <div class="table-responsive">
                                            <table id="data-table-1" class="table custom-table table-hover table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nomor Surat</th>
                                                        <th>Jenis Surat</th>
                                                        <th>Nama Karyawan</th>
                                                        <th>NID</th>
                                                        <th>Jenis Pemeriksaan</th>
                                                        <th>Rumah Sakit</th>
                                                        <th>Kelas Rawat Inap</th>
                                                        <th>Status Pengajuan</th>
                                                        <th>Status Email</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($formjaminan_personal as $i => $item)
                                                        <tr>
                                                            <td>{{ $i + 1 }}</td>
                                                            <td>{{ $item->nomor_surat }}</td>
                                                            <td>{{ $item->jenis_surat }}</td>
                                                            <td>{{ $item->karyawan['nama_karyawan'] ?? '' }}</td>
                                                            <td>{{ $item->karyawan['nid'] ?? ''}}</td>
                                                            <td>{{ $item->jenisPemeriksaan['jenis_pemeriksaan'] ?? '' }}</td>
                                                            <td>{{ $item->rumahSakit['nama_rumah_sakit'] ?? '' }}</td>
                                                            <td>{{ $item->jenis_kelas }} /
                                                                {{ number_format($item->harga) }}
                                                            </td>
                                                            <td>{{ $item->status_pengajuan }}</td>
                                                            <td>
                                                                @if ($item->status_email == true)
                                                                    SUDAH DIKIRIM
                                                                @else
                                                                    BELUM DIKIRM
                                                                @endif
                                                            </td>
                                                            <td class="text-right" align="center">
                                                                <div class="table-action">
                                                                    <a href="{{ url('/admin/form-jaminan/' . $item->id) }}"
                                                                        title="View FormJaminan">
                                                                        <button class="btn btn-sm btn-info">
                                                                            <span class="lnr lnr-eye"></span>View
                                                                        </button>
                                                                    </a>
                                                                    @if ($item->status_pengajuan == 'Sudah Disetujui Senior Manager' || $item->status_pengajuan == 'Sudah Disetujui MKAD')
                                                                        @if ($item->file_pdf)
                                                                            <a href="{{ url('/admin/form-jaminan/' . $item->id . '/email') }}"
                                                                                title="Email FormJaminan">
                                                                                <button class="btn btn-sm btn-secondary">
                                                                                    <span
                                                                                        class="lnr lnr-envelope"></span>Email
                                                                                </button>
                                                                            </a>
                                                                            <a href="{{ asset('generate-pdf/' . $item->file_pdf) }}"
                                                                                title="Print PDF FormJaminan"
                                                                                target="_blank">
                                                                                <button class="btn btn-sm btn-success">
                                                                                    <span
                                                                                        class="lnr lnr-printer"></span>Print
                                                                                    PDF
                                                                                </button>
                                                                            </a>
                                                                        @else
                                                                            <a href="{{ url('/admin/form-jaminan/' . $item->id . '/generate_PDF') }}"
                                                                                title="Print PDF FormJaminan">
                                                                                <button class="btn btn-sm btn-success">
                                                                                    <span
                                                                                        class="lnr lnr-printer"></span>Generate
                                                                                    PDF
                                                                                </button>
                                                                            </a>
                                                                        @endif
                                                                    @endif
                                                                    @if (!$item->file_pdf)
                                                                    <a href="{{ url('/admin/form-jaminan/' . $item->id . '/edit') }}"
                                                                        title="Edit FormJaminan">
                                                                        <button class="btn btn-sm btn-primary">
                                                                            <span class="lnr lnr-eye"></span>Edit
                                                                        </button>
                                                                    </a> 
                                                                    @endif
                                                                    <button type="button" class="btn btn-sm btn-danger"
                                                                        data-toggle="modal"
                                                                        data-target="#deleteConfirm{{ $item->id }}">
                                                                        <span class="lnr lnr-trash">Delete</span>
                                                                    </button>
                                                                    <div class="modal fade"
                                                                        id="deleteConfirm{{ $item->id }}" tabindex="-1"
                                                                        role="dialog" aria-labelledby="myModalLabel"
                                                                        aria-hidden="true">
                                                                        <div class="modal-dialog modal-sm">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h4 class="modal-title"
                                                                                        id="myModalLabel">Delete
                                                                                        Confirmation</h4>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    Are you sure want to delete this record?
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button"
                                                                                        class="btn btn-default"
                                                                                        data-dismiss="modal">Close
                                                                                    </button>
                                                                                    {!! Form::open([
    'method' => 'DELETE',
    'url' => ['/admin/form-jaminan', $item->id],
    'style' => 'display:inline',
]) !!}
                                                                                    {!! Form::button('Delete', [
    'type' => 'submit',
    'class' => 'btn btn-danger btn-sm',
    'title' => 'Confirm Delete',
]) !!}
                                                                                    {!! Form::close() !!}
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
                                <div class="tab-pane fade" id="pills-keluarga" role="tabpanel"
                                    aria-labelledby="pills-keluarga-tab">
                                    <!--Content table-->
                                    <li class="nav-item float-right">
                                        <button class="btn btn-theme text-white ctm-border-radius button-1"
                                            data-toggle="modal" data-target="#add-FormJaminanKeluarga"> <i
                                                class="fa fa-plus mx-2"></i>Buat Surat Jaminan</button>
                                    </li><br><br>
                                    <div class="table-back employee-office-table">
                                        <div class="table-responsive">
                                            <table id="data-table-2" class="table custom-table table-hover table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nomor Surat</th>
                                                        <th>Jenis Surat</th>
                                                        <th>Nama Karyawan</th>
                                                        <th>NID</th>
                                                        <th>Nama Pasien</th>
                                                        <th>Jenis Pemeriksaan</th>
                                                        <th>Rumah Sakit</th>
                                                        <th>Kelas Rawat Inap</th>
                                                        <th>Status Pengajuan</th>
                                                        <th>Status Email</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($formjaminan_keluarga as $i => $item)
                                                        <tr>
                                                            <td>{{ $i + 1 }}</td>
                                                            <td>{{ $item->nomor_surat }}</td>
                                                            <td>{{ $item->jenis_surat }}</td>
                                                            <td>{{ $item->karyawan['nama_karyawan'] ?? '' }}</td>
                                                            <td>{{ $item->karyawan['nid'] ?? '' }}</td>
                                                            <td>{{ $item->nama_pasien }}</td>
                                                            <td>{{ $item->jenisPemeriksaan['jenis_pemeriksaan'] ?? '' }}</td>
                                                            <td>{{ $item->rumahSakit['nama_rumah_sakit'] ?? '' }}</td>
                                                            <td>{{ $item->jenis_kelas }} /
                                                                {{ number_format($item->harga) }}</td>
                                                            <td>{{ $item->status_pengajuan }}</td>
                                                            <td>
                                                                @if ($item->status_email == true)
                                                                    SUDAH DIKIRIM
                                                                @else
                                                                    BELUM DIKIRM
                                                                @endif
                                                            </td>
                                                            <td class="text-right" align="center">
                                                                <div class="table-action">
                                                                    <a href="{{ url('/admin/form-jaminan/' . $item->id) }}"
                                                                        title="View FormJaminan">
                                                                        <button class="btn btn-sm btn-info">
                                                                            <span class="lnr lnr-eye"></span>View
                                                                        </button>
                                                                    </a>
                                                                    @if ($item->status_pengajuan == 'Sudah Disetujui Senior Manager' || $item->status_pengajuan == 'Sudah Disetujui MKAD')
                                                                        @if ($item->file_pdf)
                                                                            <a href="{{ url('/admin/form-jaminan/' . $item->id . '/email') }}"
                                                                                title="Email FormJaminan">
                                                                                <button class="btn btn-sm btn-secondary">
                                                                                    <span
                                                                                        class="lnr lnr-envelope"></span>Email
                                                                                </button>
                                                                            </a>
                                                                            <a href="{{ asset('generate-pdf/' . $item->file_pdf) }}"
                                                                                title="Print PDF FormJaminan">
                                                                                <button class="btn btn-sm btn-success">
                                                                                    <span
                                                                                        class="lnr lnr-printer"></span>Print
                                                                                    PDF
                                                                                </button>
                                                                            </a>
                                                                        @else
                                                                            <a
                                                                                href="{{ url('/admin/form-jaminan/' . $item->id . '/generate_PDF') }}">
                                                                                <button class="btn btn-sm btn-success">
                                                                                    <span
                                                                                        class="lnr lnr-printer"></span>Generate
                                                                                    PDF
                                                                                </button>
                                                                            </a>
                                                                        @endif
                                                                    @endif
                                                                    @if (!$item->file_pdf)  
                                                                    <a href="{{ url('/admin/form-jaminan/' . $item->id . '/edit') }}"
                                                                        title="Edit FormJaminan">
                                                                        <button class="btn btn-sm btn-primary">
                                                                            <span class="lnr lnr-eye"></span>Edit
                                                                        </button>
                                                                    </a>
                                                                    @endif
                                                                    <button type="button" class="btn btn-sm btn-danger"
                                                                        data-toggle="modal"
                                                                        data-target="#deleteConfirm{{ $item->id }}">
                                                                        <span class="lnr lnr-trash">Delete</span>
                                                                    </button>
                                                                    <div class="modal fade"
                                                                        id="deleteConfirm{{ $item->id }}" tabindex="-1"
                                                                        role="dialog" aria-labelledby="myModalLabel"
                                                                        aria-hidden="true">
                                                                        <div class="modal-dialog modal-sm">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h4 class="modal-title"
                                                                                        id="myModalLabel">Delete
                                                                                        Confirmation</h4>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    Are you sure want to delete this record?
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button"
                                                                                        class="btn btn-default"
                                                                                        data-dismiss="modal">Close
                                                                                    </button>
                                                                                    {!! Form::open([
    'method' => 'DELETE',
    'url' => ['/admin/form-jaminan', $item->id],
    'style' => 'display:inline',
]) !!}
                                                                                    {!! Form::button('Delete', [
    'type' => 'submit',
    'class' => 'btn btn-danger btn-sm',
    'title' => 'Confirm Delete',
]) !!}
                                                                                    {!! Form::close() !!}
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
    </div>
    <!--/Content-->
    <div class="modal fade" id="add-FormJaminanPersonal" role="document">
        <div class="modal-dialog modal-dialog-centered modal modal-lg">
            <div class="modal-content ">
                <!-- Modal body -->
                <div class="modal-body style-add-modal">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title mb-3">Buat Surat Jaminan Personal</h4>
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </div>
                    @endif

                    {!! Form::open(['url' => '/admin/form-jaminan', 'class' => '', 'enctype' => 'multipart/form-data']) !!}

                    @include ('admin.form-jaminan.form', ['formMode' => 'create'])

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="add-FormJaminanKeluarga" role="document">
        <div class="modal-dialog modal-dialog-centered modal modal-lg">
            <div class="modal-content ">
                <!-- Modal body -->
                <div class="modal-body style-add-modal">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title mb-3">Buat Surat Jaminan Keluarga</h4>
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </div>
                    @endif

                    {!! Form::open(['url' => '/admin/form-jaminan', 'class' => '', 'enctype' => 'multipart/form-data']) !!}

                    @include ('admin.form-jaminan.form_keluarga', ['formMode' => 'create'])

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $("#id_karyawan_keluarga").change(function() {
                $.ajax({
                    type: "GET",
                    url: "{{ url('/admin/form-jaminan/kelas') . '/' }}" + $("#id_karyawan_keluarga").val(),
                    success: function(res) {
                        console.log(res);
                        $("#id_kelas_rawat_inap_keluarga").val(res.kelas.jenis_kelas + ' / ' + res.kelas
                            .harga)
                    }
                });
            }),
            $("#id_karyawan_personal").change(function() {
                $.ajax({
                    type: "GET",
                    url: "{{ url('/admin/form-jaminan/kelas') . '/' }}" + $("#id_karyawan_personal").val(),
                    success: function(res) {
                        console.log(res);
                        $("#id_kelas_rawat_inap_personal").val(res.kelas.jenis_kelas + ' / ' + res.kelas
                            .harga)
                    }
                });
            }),
            $("#id_karyawan_keluarga").change(function() {
                $.ajax({
                    type: "GET",
                    url: "{{ url('/admin/form-jaminan/pasien') . '/' }}" + $("#id_karyawan_keluarga").val(),
                    success: function(res) {
                        console.log(res);
                        $("#nama_pasien1").html(res.pasien.istri)
                        $("#nama_pasien2").html(res.pasien.anak_1)
                        $("#nama_pasien3").html(res.pasien.anak_2)
                        $("#nama_pasien4").html(res.pasien.anak_3)
                    }
                });
            }),
            $("#id_karyawan_personal").change(function() {
                $.ajax({
                    type: "GET",
                    url: "{{ url('/admin/form-jaminan/pasien') . '/' }}" + $("#id_karyawan_personal").val(),
                    success: function(res) {
                        console.log(res);
                        $("#nama_pasien").val(res.pasien.nama_karyawan)
                    }
                });
            })
    </script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple-personal').select2({
               theme: 'bootstrap4',
               dropdownParent: $('#add-FormJaminanPersonal')
           });
        });
    </script>
     <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple-keluarga').select2({
               theme: 'bootstrap4',
               dropdownParent: $('#add-FormJaminanKeluarga')
           });
        });
    </script>
     <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple-sakit').select2({
               theme: 'bootstrap4',
               dropdownParent: $('#add-FormJaminanKeluarga')
           });
        });
    </script>
@stop
