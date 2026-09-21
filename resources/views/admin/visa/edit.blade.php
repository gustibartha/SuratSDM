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
                                                    <li class="breadcrumb-item d-inline-block active">Visa</li>
                                                </ol>
                                                <h4 class="text-dark">Ubah Data</h4>
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
                            <h4 class="card-title float-left mb-0 mt-2"> Ubah Data Visa</h4>
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
                        <form action="{{url('admin/visa/'.$visa->id.'/edit')}}" method="POST">
                            {{ csrf_field() }}
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group{{ $errors->has('karyawan_id') ? 'has-error' : '' }}">
                                        <label for="sifat" class="control-label">{{ 'Karyawan' }}</label>
                                        <br>
                                        <select name="karyawan_id" id="id-karyawan" class="js-example-basic-multiple form-control">
                                            <option value="" disabled selected>Silahkan Dipilih</option>
                                            @foreach ($karyawans as $item) 
                                                <option value="{{ $item->id }}" {{$visa->karyawan_id == $item->id ? 'selected' : ''}}>{{ $item->nama_karyawan }}</option> 
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group{{ $errors->has('jenis') ? 'has-error' : '' }}">
                                        <label for="jenis" class="control-label">{{ 'Jenis Surat' }}</label>
                                        <input class="form-control" name="jenis" type="text" id="jenis" placeholder="ex . Regular" value="{{$visa->jenis}}">
                                        {!! $errors->first('jenis', '<p class="help-block">:message</p>') !!}
                                    </div>
                                    <div class="form-group{{ $errors->has('tujuan') ? 'has-error' : '' }}">
                                        <label for="tujuan" class="control-label">{{ 'Tujuan' }}</label>
                                        <input class="form-control" name="tujuan" type="text" id="tujuan" placeholder="ex . The Embassy of Australia" value="{{$visa->tujuan}}">
                                        {!! $errors->first('tujuan', '<p class="help-block">:message</p>') !!}
                                    </div>
                                    <div class="form-group{{ $errors->has('alamat') ? 'has-error' : '' }}">
                                        <label for="alamat" class="control-label">{{ 'Alamat' }}</label>
                                        <input class="form-control" name="alamat" type="text" id="alamat"  placeholder="ex. jalan......" value="{{$visa->alamat}}">
                                        {!! $errors->first('alamat', '<p class="help-block">:message</p>') !!}
                                    </div>
                                    <div class="form-group{{ $errors->has('tanggal_mulai') ? 'has-error' : '' }}">
                                        <label for="tanggal_mulai" class="control-label">{{ 'Tanggal Mulai' }}</label>
                                        <input class="form-control" name="tanggal_mulai" type="date" id="tanggal_mulai"  placeholder="ex . Administrasi KTA BNI Flexi" value="{{$visa->tanggal_mulai}}">
                                        {!! $errors->first('tanggal_mulai', '<p class="help-block">:message</p>') !!}
                                    </div>
                                    <div class="form-group{{ $errors->has('tanggal_selesai') ? 'has-error' : '' }}">
                                        <label for="tanggal_selesai" class="control-label">{{ 'Tanggal Selesai' }}</label>
                                        <input class="form-control" name="tanggal_selesai" type="date" id="tanggal_selesai"  placeholder="ex . Administrasi KTA BNI Flexi" value="{{$visa->tanggal_selesai}}">
                                        {!! $errors->first('tanggal_selesai', '<p class="help-block">:message</p>') !!}
                                    </div>
                                    <div class="form-group{{ $errors->has('negara_tujuan') ? 'has-error' : '' }}">
                                        <label for="negara_tujuan" class="control-label">{{ 'Negara Tujuan' }}</label>
                                        <input class="form-control" name="negara_tujuan" type="text" id="negara_tujuan"  placeholder="ex . Australia" value="{{$visa->negara_tujuan}}">
                                        {!! $errors->first('negara_tujuan', '<p class="help-block">:message</p>') !!}
                                    </div>
                                    <div class="form-group{{ $errors->has('keperluan') ? 'has-error' : '' }}">
                                        <label for="keperluan" class="control-label">{{ 'Keperluan' }}</label>
                                        <input class="form-control" name="keperluan" type="text" id="keperluan"  placeholder="ex . vacation" value="{{$visa->keperluan}}">
                                        {!! $errors->first('keperluan', '<p class="help-block">:message</p>') !!}
                                    </div>
                                    <div class="store-product pt-5">
                                        <div class="store-product-header d-flex align gap-3">
                                            <h6 class="my-auto">Keluarga</h6>
                                            <button class="btn btn-success text-white ml-5" type="button" data-toggle="modal" data-target="#keluargaModal">Tambah Keluarga </button>
                                        </div>
                                        <div class="store-product-table">
                                            <div class="table pt-3">
                                                <table class="table table-bordered text-center">
                                                    <thead>
                                                    <tr>
                                                        <th>Nama</th>
                                                        <th>Hubungan</th>
                                                        <th>Nomor Passport</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="tb-keluarga">
                                                        @foreach ($keluargas as $keluarga)
                                                        <tr class="tr-product">
                
                                                            <td>
                                                                <input type="text" class="form-control" value="{{$keluarga->nama}}" name="nama_keluarga[]">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" value="{{$keluarga->hubungan}}" name="hubungan[]">
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" value="{{$keluarga->nomor_passport}}" name="passport[]">
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger" onclick="deleteRow(this)">
                                                                   Hapus    
                                                                </button>
                                                            </td> 
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mt-5" align="right">
                                <button class="btn btn-success btn-lg" type="submit">Submit</button>
                                <a href="#" onClick="javascript:history.go(-1)" class="btn btn-danger btn-lg">Cancel and Back</a>
                            </div>
                            
                        </form>
                </div>
            </div>
        </div>
    </div>
    <!-- product Modal -->
    <div class="modal fade" id="keluargaModal" role="document">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content ">
        <div class="modal-header">
          <h5 class="modal-title" id="keluargaModalLabel">Tambah Keluarga</h5>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label for="role" class="form-label">Nama</label>
                <select name="" id="keluarga_nama" class="form-control">
                    @if ($visa->karyawan->istri)
                        <option>{{$visa->karyawan->istri}}</option>
                    @endif
                    @if ($visa->karyawan->anak_1)
                        <option>{{$visa->karyawan->anak_1}}</option>
                    @endif
                    @if ($visa->karyawan->anak_2)
                        <option>{{$visa->karyawan->anak_2}}</option>
                    @endif
                    @if ($visa->karyawan->anak_3)
                        <option>{{$visa->karyawan->anak_3}}</option>
                    @endif
                </select>
            </div> 
            <div class="mb-3">
                <label for="role" class="form-label">Hubungan</label>
                <select name="" id="keluarga_hubungan" class="form-control">
                    <option>Husband</option>
                    <option>Wife</option>
                    <option>Son</option>
                    <option>Daughter</option>
                </select>
            </div> 
            <div class="mb-3">
                <label for="role" class="form-label">Nomor Passport</label>
                <input type="text" class="form-control" id="passport_nomor">
            </div> 
        </div>
        <div class="modal-footer d-flex justify-content-center">
          <button type="button" class="btn btn-primary" id="btn-keluarga-add">Save changes</button>
        </div>
      </div>
    </div>
  </div>
  @section('scripts')
  <script>
    $("#id-karyawan").change(function() {
                    $.ajax({
                        type: "GET",
                        url: "{{ url('/admin/form-jaminan/pasien') . '/' }}" + $("#id-karyawan").val(),
                        success: function(res) {
                            console.log(res);
                            $("#keluarga_nama").empty();
                            $(".tb-keluarga").empty();
                            if (res.pasien.istri) {
                                $("#keluarga_nama").append(`<option>${res.pasien.istri}</option>`)
                                
                            }
                            if (res.pasien.anak_1) {
                                $("#keluarga_nama").append(`<option>${res.pasien.anak_1}</option>`)
                                
                            }
                            if (res.pasien.anak_2) {
                                $("#keluarga_nama").append(`<option>${res.pasien.anak_2}</option>`)
                                
                            }
                            if (res.pasien.anak_3) {
                                $("#keluarga_nama").append(`<option>${res.pasien.anak_3}</option>`)
                                
                            }
                            // $("#nama_pasien2").html(res.pasien.anak_1)
                            // $("#nama_pasien3").html(res.pasien.anak_2)
                            // $("#nama_pasien4").html(res.pasien.anak_3)
                        }
                    });
    }),
    $("#btn-keluarga-add").click(function () {
        var getnumber = $('.tb-product').find('.tr-product').last().find('.tr-number').val()

        var id = $('#product_name').val()
        // var name = $('#product_name option:selected').text()
        var name = $('#keluarga_nama').val()
        var hubungan = $('#keluarga_hubungan option:selected').text()
        var passport = $('#passport_nomor').val()

        $('.tb-keluarga').append(
            `<tr class="tr-product">
                
                <td>
                    <input type="text" class="form-control" value="${name}" name="nama_keluarga[]">
                </td>
                <td>
                    <input type="text" class="form-control" value="${hubungan}" name="hubungan[]">
                </td>
                <td>
                    <input type="text" class="form-control" value="${passport}" name="passport[]">
                </td>
                <td>
                    <button type="button" class="btn btn-danger" onclick="deleteRow(this)">
                       Hapus    
                    </button>
                </td>
            </tr>`
        )
        $('#keluargaModal').hide();
        $('.modal-backdrop').hide();
    });


    function deleteRow(e){
        $(e).parent().parent().remove()      
    }
</script>
@endsection
    <!--/Content-->
@endsection
