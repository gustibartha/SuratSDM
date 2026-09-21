@extends('layouts.backend')

@section('content')
    <!-- Content -->
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
                                                    <li class="breadcrumb-item d-inline-block active">Surat Jaminan</li>
                                                </ol>
                                                <h4 class="text-dark">Edit Surat Jaminan</h4>
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
                            <h4 class="card-title float-left mb-0 mt-2"> Surat Jaminan Edit</h4>
                            <ul class="nav nav-tabs float-right border-0 tab-list-emp">
                            </ul>
                        </div>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade in" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">×</span></button>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </div>
                    @endif

                    {!! Form::model($formjaminan, [
    'method' => 'PATCH',
    'url' => ['/admin/form-jaminan', $formjaminan->id],
    'class' => '',
    'enctype' => 'multipart/form-data',
]) !!}
                    @if ($formjaminan->jenis_surat == 'personal')
                    @include ('admin.form-jaminan.form', ['formMode' => 'edit'])
                    @else
                    @include ('admin.form-jaminan.form_keluarga', ['formMode' => 'edit'])
                    @endif

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!--/Content-->
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
@stop
