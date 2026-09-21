@extends('layouts.backend-dokter')

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
                                                    <li class="breadcrumb-item d-inline-block active">Ubah Password</li>
                                                </ol>
                                                <h4 class="text-dark">Ubah Password</h4>
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
                            <h4 class="card-title float-left mb-0 mt-2"> Ubah Password</h4>
                            <ul class="nav nav-tabs float-right border-0 tab-list-emp">
                            </ul>
                        </div>
                    </div>
                   
                    <div class="card shadow-sm ctm-border-radius">
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <ul>
                                    <li>{!! \Session::get('success') !!}</li>
                                </ul>
                            </div>
                        @endif

                        @if (\Session::has('failed'))
                            <div class="alert alert-danger">
                                <ul>
                                    <li>{!! \Session::get('failed') !!}</li>
                                </ul>
                            </div>
                        @endif


                        <div class="card-body align-center">
                           <form action="{{url('dokter/ubah-password')}}" class="col-lg-6" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="exampleInputPassword1">Password Lama</label>
                                <input type="password" class="form-control" id="exampleInputPassword1" name="password_lama">
                                @if($errors->has('password_lama'))
                                    <div class="text-danger">{{ $errors->first('password_lama') }}</div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Password Baru</label>
                                <input type="password" class="form-control" id="exampleInputPassword1" name="password">
                                @if($errors->has('password'))
                                    <div class="text-danger">{{ $errors->first('password') }}</div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="exampleInputPassword1" name="konfirmasi_password">
                                @if($errors->has('konfirmasi_password'))
                                    <div class="text-danger">{{ $errors->first('konfirmasi_password') }}</div>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-success float-right">Submit</button>
                           </form>
                        </div>
                    </div>

                
                </div>
            </div>
        </div>
    </div>
    <!--/Content-->
@endsection
