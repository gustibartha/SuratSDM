<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keterangan Kerja</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
            font-size: 12px;
            line-height: 1.5;
            margin: 40px;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 300px;
            margin-bottom: 10px;
        }
        .header h1 {
            font-size: 20px;
            margin: 0;
        }
        .header p {
            margin: 0;
        }
        .content {
            margin-top: 20px;
        }
        .content p {
            margin: 5px 0;
        }
        .content table {
            width: 100%;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .content table td {
            vertical-align: top;
        }
        .content table td:first-child {
            width: 150px;
        }
        .signature {
            margin-top: 15px;
            text-align: left;
        }
        .signature p {
            margin: 5px 0;
        }
        .signature .stamp {
            margin-top: 30px;
            position: relative;
        }
        .footer {
            position: absolute;
            bottom:0;
            left:0;
            color:blue;
            margin-top: 20px;
            font-size: 11px;
            line-height: 1;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('vendor/lakers/img/logo-pjb.png') }}" alt="">
    </div>

    <div class="content">
        <div style="display: inline">
            <span style="float-left">Nomor <span style="margin-left:30px">:</span> {{$suratketerangan->nomor_surat}}</span>
            <span style="float-right;margin-left:220px">Jakarta, {{date('d F Y')}}</span>
        </div>
        <p>Sifat <span style="margin-left: 42px;">:</span> {{$suratketerangan->sifat}}</p>
        <p>Lampiran <span style="margin-left:16px">:</span> - </p>
        <div style="margon-top:20px;margin-bottom: 20px;">

            <p style="padding-right:200px; ">Kepada Yth,<br>{{$suratketerangan->penerima}} <br>{{$suratketerangan->alamat_penerima}}</p>
        </div>

        <p>Perihal <span style="margin-left:32px;">:</span> Surat Keterangan Kerja a.n {{$suratketerangan->karyawan->nama_karyawan}}</p>

        <p>Yang bertanda tangan di bawah ini:</p>

        <table style="margin-left:30px;">
            <tr>
                <td>Nama</td>
                <td>: {{$sm[0]['name']}}</td>
            </tr>
            <tr>
                <td>NID</td>
                <td>: {{$sm[0]['nid']}} </td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>: {{$sm[0]['jabatan']}}</td>
            </tr>
        </table>

        <p>Dengan ini menyatakan bahwa:</p>

        <table style="margin-left:30px">
            <tr>
                <td>Nama</td>
                <td>: {{$suratketerangan->karyawan->nama_karyawan}}</td>
            </tr>
            <tr>
                <td>NID</td>
                <td>: {{$suratketerangan->karyawan->nid}}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>: {{$suratketerangan->karyawan->jabatan}}</td>
            </tr>
            <tr>
                <td>Tanggal Masuk</td>
                <td>: {{ $suratketerangan->tanggal_masuk_karyawan ?  date('d F Y', strtotime($suratketerangan->tanggal_masuk_karyawan)) : date('d F Y', strtotime($suratketerangan->karyawan->tanggal_masuk_karyawan)) }}</td>
            </tr>
        </table>

        <p>adalah benar karyawan dari PT PLN Nusantara Power Unit Pembangkitan Muara Karang dan masih aktif bekerja hingga saat ini.</p>

        <p>Demikian surat ini dibuat untuk keperluan {{$suratketerangan->keperluan}} dan dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    @if ($suratketerangan->rangking == 3)
        <div class="signature" style="display: inline">
            <p>Jakarta, {{date('d F Y')}}</p>
            <p style="padding-bottom:70px;">{{$sm[0]['jabatan']}}</p>
            <div class="stamp" >
                {{-- <img src="{{public_path('qrcode/sm.png')}}" alt="" width="85"> --}}
            </div>
            <img src="{{public_path('qrcode/mkad.png')}}" alt="" width="40" style="padding-right:20px;margin-top:30px;display:inline">
            <p style="display: inline;">{{$sm[0]['name']}} </p>
                
        <img src="{{public_path('qrcode/asman.png')}}" alt="" width="40" style="padding-left:30px;margin-top:30px;">
    @else
        <div class="signature" style="display: inline">
            <p>Jakarta, {{date('d F Y')}}</p>
            <p>{{$sm[0]['jabatan']}}</p>
            <div class="stamp">
                <img src="{{public_path('qrcode/sm.png')}}" alt="" width="85">
            </div>
            <img src="{{public_path('qrcode/mkad.png')}}" alt="" width="40" style="padding-right:20px;margin-top:30px;display:inline">
            <p style="display: inline;">{{$sm[0]['name']}} </p>
                
        <img src="{{public_path('qrcode/asman.png')}}" alt="" width="40" style="padding-left:30px;margin-top:30px;">
    @endif
        
    </div>
    <p style="padding-left:63px;margin-top:-7px;">NID: {{$sm[0]['nid']}}</p>

    <div class="footer">
        <p>PT PLN NUSANTARA POWER, UNIT PEMBANGKITAN MUARA KARANG</p>
        <p>Jl. Raya Pluit Karang Ayu No. 01, Jakarta Utara 14450</p>
        <p>Telp: (021) 6600054, 6692784 | Faks: (021) 6692806</p>
        <p>Email: upmrk@plnnusantarapower.co.id</p>
    </div>
</body>
</html>
