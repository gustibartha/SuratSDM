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
                                                    <li class="breadcrumb-item d-inline-block active">Formjaminan</li>
                                                </ol>
                                                <h4 class="text-dark">Form Jaminan Detail</h4>
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
                            <h4 class="card-title float-left mb-0 mt-2">Form Jaminan Detail</h4>
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
                                            {{-- <tr>
                                                <th>ID</th>
                                                <th>{{ $formjaminan->id }}</th>
                                            </tr> --}}
                                            <tr>
                                                <th> Nomor Surat </th>
                                                <td> {{ $formjaminan->nomor_surat }} </td>
                                            </tr>
                                            <tr>
                                                <th> Jenis Surat </th>
                                                <td> {{ $formjaminan->jenis_surat }} </td>
                                            </tr>
                                            <tr>
                                                <th> Karyawan </th>
                                                <td> {{ $formjaminan->karyawan['nama_karyawan'] }} </td>
                                            </tr>
                                            <tr>
                                                <th> Jenis Pemeriksaan </th>
                                                <td> {{ $formjaminan->jenisPemeriksaan['jenis_pemeriksaan'] }} </td>
                                            </tr>
                                            <tr>
                                                <th> Nama Pasien </th>
                                                <td> {{ $formjaminan->nama_pasien }} </td>
                                            </tr>
                                            <tr>
                                                <th> Rumah Sakit </th>
                                                <td> {{ $formjaminan->rumahSakit['nama_rumah_sakit'] }} </td>
                                            </tr>
                                            <tr>
                                                <th> Biaya Rumah Sakit </th>
                                                <td> {{ $formjaminan->karyawan['kelasRawatInap']['jenis_kelas'] }} /
                                                    {{ $formjaminan->karyawan['kelasRawatInap']['harga'] }} </td>
                                            </tr>
                                            <tr>
                                                <th> Status Pengajuan </th>
                                                <td> {{ $formjaminan->status_pengajuan }} </td>
                                            </tr>
                                            <tr>
                                                <th> Status Email </th>
                                                @if ($formjaminan->status_email)
                                                    <td> SUDAH DIKIRM </td>
                                                @else
                                                    <td> BELUM DIKIRIM </td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td colspan="10" align="center">
                                                    <a href="{{ url('/admin/form-jaminan') }}"><button
                                                            class="btn btn-sm btn-warning"><span
                                                                class="lnr lnr-arrow-left">Back</span></button></a>
                                                    <a
                                                        href="{{ url('/admin/form-jaminan/' . $formjaminan->id . '/edit') }}"><button
                                                            class="btn btn-sm btn-primary" title="Edit FormJaminan"><span
                                                                class="lnr lnr-pencil">Edit</span></button></a>
                                                    {!! Form::open([
    'method' => 'DELETE',
    'url' => ['admin/formjaminan', $formjaminan->id],
    'style' => 'display:inline',
]) !!}
                                                    {!! Form::button('<span class="lnr lnr-trash">Delete</span>', [
    'type' => 'submit',
    'class' => 'btn btn-sm btn-danger',
    'title' => 'Delete FormJaminan',
    'onclick' => 'return confirm("Confirm delete?")',
]) !!}
                                                    {!! Form::close() !!}
                                                </td>
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
