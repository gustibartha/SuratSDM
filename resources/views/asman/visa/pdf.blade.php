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
            line-height: 0.9;
            margin: 40px;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 230px;
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
        .signature {
            margin-top: 20px;
            text-align: left;
        }
        .signature p {
            margin: 5px 0;
        }
        .footer {
            position: absolute;;
            bottom:0;
            color:blue;
            font-size: 8px;
            line-height: 0.7
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('vendor/lakers/img/logo-pjb.png') }}" alt="">
    </div>

    <div class="content">
        <div style="display: inline">
            <span style="float-left">Number <span style="margin-left:34px">:</span> {{$visa->nomor_surat}}</span>
            <span style="float-right;margin-left:220px">Jakarta, {{date('d F Y')}}</span>
        </div>
        <div style="line-height: 1">
            <p>Type <span style="margin-left: 50px;">:</span> {{$visa->jenis}}</p>
            <p>Attachment <span style="margin-left:16px">:</span> - </p>
        </div>
        <div style="margin-top:10px;margin-bottom: 20px;line-height:1.2">
                
            <p>To : <br> {{$visa->tujuan}}</p>
            <p style="padding-right:300px;">{{$visa->alamat}}</p>
        </div>

        <p>Subject <span style="margin-left:32px;">:</span> Visa Application Letter to Embassy {{$visa->tujuan}}</p>

        <p>Dear Sir / Madam:</p>
        <p>I. The Undersigned : </p>
        <table style="margin-left:30px;">
            <tr>
                <td>Name</td>
                <td>: {{$sm[0]['name']}}</td>
            </tr>
            <tr>
                <td>Position / Relation</td>
                <td>: {{$sm[0]['jabatan']}}</td>
            </tr>
        </table>

        <p>Herewith respectfully request the kind of assistance of the Embassy in issuing Visa for following
            person:</p>

        <table>
            <tr>
                <td>1</td>
                <td>Name</td>
                <td>: {{$visa->karyawan->nama_karyawan}}</td>
            </tr>
            <tr>

                <td></td>
                <td>Position / Relation</td>
                <td>: {{$visa->karyawan->jabatan}}</td>
            </tr>
            <tr>
                <td></td>
                <td>Employee ID</td>
                <td>: {{$visa->karyawan->nid}}</td>
            </tr>
            @foreach ($keluargas as $item)
            <tr>
                <td>{{$loop->iteration + 1}}</td>
                <td>Name</td>
                <td>: {{$item->nama}}</td>
            </tr>
            <tr>

                <td></td>
                <td>Relation</td>
                <td>: {{$item->hubungan}} of {{$visa->karyawan->nama_karyawan}}</td>
            </tr>
            <tr>
                <td></td>
                <td>Passport Number</td>
                <td>: {{$item->nomor_passport}}</td>
            </tr>
            @endforeach
        </table>

        <p style="text-align: justify;">The above mentioned is PT PLN Nusantara Power UP Muara Karang employee and his wife,
            would like to go to {{$visa->negara_tujuan}} for {{$visa->keperluan}} on {!! \Carbon\Carbon::parse($visa->tanggal_mulai)->format('F, j') !!}<sup>{!! \Carbon\Carbon::parse($visa->tanggal_mulai)->format('S') !!}</sup> until {!! \Carbon\Carbon::parse($visa->tanggal_selesai)->format('F, j') !!}<sup>{!! \Carbon\Carbon::parse($visa->tanggal_selesai)->format('S') !!}</sup> {!! \Carbon\Carbon::parse($visa->tanggal_selesai)->format('Y') !!}.</p>
            <p style="text-align: justify;"> 
                All expenses incurred on the trip will be granted by himself and he will not seek any employment or
                    permanent stay in your country and will return to Indonesia after the end of the {{$visa->keperluan}} and also
                    promise to obey every regulation in your country.
            </p> 
            <p style="text-align: justify;">
                We would like to request the kind assistance the {{$visa->tujuan}} to process the visa at the
                earliest convenience which would be enable to depart from Jakarta on date {!! \Carbon\Carbon::parse($visa->tanggal_selesai)->format('F Y') !!}
            </p>

        <p style="text-align: justify;">Thank you for your kind assistance and good cooperation..</p>
    </div>
    @if ($visa->rangking == 1)
        <div class="signature">
            <p style="padding-bottom:70px;">{{$sm[0]['jabatan']}}</p>
            <div class="stamp" style="margin-top:15px;margin-bottom:15px;">
            {{-- <img src="{{public_path('qrcode/sm.png')}}" alt="" width="85"> --}}
            </div>

                {{-- <img src="{{public_path('qrcode/mkad.png')}}" alt="" width="40" style="padding-right:20px;margin-top:15px;"> --}}
                <p style="display:inline !important;text-align:center;"> {{$sm[0]['name']}}</p>
                {{-- <img src="{{public_path('qrcode/asman.png')}}" alt="" width="40" style="padding-left:20px;margin-top:15px;"> --}}
        </div>
    @else
        <div class="signature">
            <p style="padding-bottom:70px;">{{$sm[0]['jabatan']}}</p>
            <div class="stamp" style="margin-top:15px;margin-bottom:15px;">
            {{-- <img src="{{public_path('qrcode/sm.png')}}" alt="" width="85"> --}}
            </div>

                {{-- <img src="{{public_path('qrcode/mkad.png')}}" alt="" width="40" style="padding-right:20px;margin-top:15px;"> --}}
                <p style="display:inline !important;text-align:center;"> {{$sm[0]['name']}}</p>
                <img src="{{public_path('qrcode/asman.png')}}" alt="" width="40" style="padding-left:20px;margin-top:15px;">
        </div>
    @endif
   

    <div class="footer">
        <p>PT PLN NUSANTARA POWER, UNIT PEMBANGKITAN MUARA KARANG</p>
        <p>Jl. Raya Pluit Karang Ayu No. 01, Jakarta Utara 14450</p>
        <p>Telp: (021) 6600054, 6692784 | Faks: (021) 6692806</p>
        <p>Email: upmrk@plnnusantarapower.co.id</p>
    </div>
</body>
</html>
