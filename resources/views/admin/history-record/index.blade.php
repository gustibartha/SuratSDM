@extends('layouts.backend')
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
                                                            href="{{ url('/admin') }}" class="text-dark">Home</a></li>
                                                    <li class="breadcrumb-item d-inline-block active">History Record</li>
                                                </ol>
                                                <h4 class="text-dark">History Record</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                        <button class="btn btn-theme text-white ctm-border-radius button-1"
                                            data-toggle="modal" data-target="#add-Karyawan"> <i
                                                class="fa fa-plus mx-2"></i>Tambah Data History</button>
                                    </li><br><br>
                                    <div class="table-back employee-office-table">
                                        <div class="table-responsive">
                                            <table id="data-table-1" class="table custom-table table-hover table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Karyawan</th>
                                                        <th>Nid</th>
                                                        <th>Riwayat Penyakit</th>
                                                        <th>Jenis Pengobatan / Tindakan</th>
                                                        <th>Riwayat Obat</th>
                                                        <th>Resume Medis</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($records as $i => $item)
                                                        <tr>
                                                            <td>{{ $i + 1 }}</td>
                                                            <td>{{ $item->karyawan->nama_karyawan ?? '' }}</td>
                                                            <td>{{ $item->karyawan->nid ?? '' }}</td>
                                                            <td class="text-ck">{{ $item->riwayat_penyakit }}</td>
                                                            <td class="text-ck">{{ $item->jenis_pengobatan }}</td>
                                                            <td class="text-ck">{{ $item->riwayat_obat }}</td>
                                                            <td class="text-ck">{{ $item->resume_medis }}</td>

                                                            <td class="text-right" align="center">
                                                                <div class="table-action">
                                                                    <a href="{{ url('/admin/history-record/' . $item->id) }}"
                                                                        title="View history-record"><button
                                                                            class="btn btn-sm btn-info"><span
                                                                                class="lnr lnr-eye"></span>View</button></a>
                                                                    <a href="{{ url('/admin/history-record/' . $item->id . '/edit') }}"
                                                                        title="Edit history-record"><button
                                                                            class="btn btn-sm btn-primary"><span
                                                                                class="lnr lnr-eye"></span>Edit</button></a>
                                                                    <button type="button" class="btn btn-sm btn-danger"
                                                                        data-toggle="modal"
                                                                        data-target="#deleteConfirm{{ $item->id }}"><span
                                                                            class="lnr lnr-trash">Delete</span></button>
                                                                    <div class="modal fade" id="deleteConfirm{{ $item->id }}"
                                                                        tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                                                                        aria-hidden="true">
                                                                        <div class="modal-dialog modal-sm">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h4 class="modal-title" id="myModalLabel">Delete
                                                                                        Confirmation</h4>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    Are you sure want to delete this record?
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-default"
                                                                                        data-dismiss="modal">Close</button>
                                                                                    {!! Form::open([
            'method' => 'DELETE',
            'url' => ['/admin/history-record', $item->id],
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
    <!--/Content-->
    <div class="modal fade" id="add-Karyawan" role="document">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content ">
                <!-- Modal body -->
                <div class="modal-body style-add-modal">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title mb-3">Tambah Data History</h4>
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </div>
                    @endif

                    {!! Form::open(['url' => '/admin/history-record', 'class' => '', 'enctype' => 'multipart/form-data']) !!}

                    @include ('admin.history-record.form', ['formMode' => 'create'])

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
 <script>
    $(document).ready(function() {
        $('.js-example-basic-multiple-history').select2({
           theme: 'bootstrap4',
           dropdownParent: $('#add-Karyawan')
       });
    });
</script>
@endsection
