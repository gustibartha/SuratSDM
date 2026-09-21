<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title> Login : Surat SDM</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('vendor/lakers') }}/img/new-logo.ico">

    <!-- vendor css -->
    <link href="{{asset('vendor/login')}}/lib/%40fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="{{asset('vendor/login')}}/lib/ionicons/css/ionicons.min.css" rel="stylesheet">

    <!-- Bracket CSS -->
    <link rel="stylesheet" href="{{asset('vendor/login')}}/css/bracket.css">
    <link rel="stylesheet" href="{{asset('vendor/login')}}/css/bracket.dark.css">
      <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{asset('vendor/sweetalert')}}/sweetalert.css">
  <style>
    /* .body-login{
        background-image: url('vendor/lakers/img/bg-suratjaminan.png') !important;
    } */
  </style>
  <!-- End SweetAlert2 -->
</head>

<body>
    <div class="d-flex align-items-center justify-content-center ht-100v" >
        <img src="{{asset('vendor/login')}}/img/geek.png" class="wd-100p ht-100p object-fit-cover" alt="">
        <div class="overlay-body d-flex align-items-center justify-content-center body-login"style="background-image: url({{asset('vendor/lakers/img/bg-suratjaminan.jpg')}});background-size:cover;" >
            <div class="login-wrapper wd-300 wd-xs-350 pd-25 pd-xs-40 rounded bd bd-white-2 bg-black-7">
                <div class="signin-logo tx-center tx-28 tx-bold tx-white">
                    <img src="{{asset('vendor/lakers/img/logo-pjb.png')}}" style="width:200px;" alt="">
                </div>
                <div class="tx-white-5 tx-center mg-b-60">Silahkan Login <br> Selamat Datang Di Aplikasi Surat SDM</div>
                @if (\Session::has('error'))
                    <div class="alert alert-danger">
                            {!! \Session::get('error') !!}
                    </div>
                @endif

                <form action="{{route('login.submit')}}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <input type="text" name="email" class="form-control fc-outline-dark" placeholder="Silahkan Masukkan Email Anda">
                    </div><!-- form-group -->
                    <div class="form-group">
                        <input type="password" name="password" class="form-control fc-outline-dark" placeholder="Silahkan Masukkan Password Anda">
                    </div><!-- form-group -->
                    <button type="submit" class="btn btn-info btn-block">Sign In</button>
                </form>
            </div><!-- login-wrapper -->
        </div><!-- overlay-body -->
    </div><!-- d-flex -->

    <script src="{{asset('vendor/login')}}/lib/jquery/jquery.min.js"></script>
    <script src="{{asset('vendor/login')}}/lib/jquery-ui/ui/widgets/datepicker.js"></script>
    <script src="{{asset('vendor/login')}}/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('vendor/sweetalert')}}/sweetalert.min.js"></script>
    @include('vendor.sweet.alert')
</body>

</html>
