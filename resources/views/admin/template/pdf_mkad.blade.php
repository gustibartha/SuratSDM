<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<style>
    * {
        font-family: "Times New Roman", Times, serif;

    }

    body {
        font-size: 14px;
        margin-left: 30px;
    }

    body img {

        margin-left: 275px;

    }

    .judul_surat {
        margin-top: -10px;

    }

    .header {
        text-decoration: underline;
    }

    .nomor {
        margin-top: -10px;
    }

    .deskripsi {
        margin-left: 40px
        font-size:14px;
    }

    .deskripsi .nama {
        font-weight: bold;
    }

    .deskripsi .jabatan {
        text-transform: uppercase;
    }

    .isi_surat .pertama {
        text-align: justify;
    }

    .informasi_pasien {
        margin-left: 40px;
    }

    .informasi_pasien .nama {
        font-weight: bold;
    }

    .informasi_pasien .rumah_sakit {
        font-weight: bold;
    }

    .penjelasan {
        text-align: justify;
    }

    .ttd {
        text-align: justify;
    }

    .ttd-mkad {
        display: inline-block;
        margin-top: 10px;
        text-transform: uppercase;
        font-weight: bold;
    }
    .paraf{
        margin-top: -22px;
        margin-left:-290px;
    }

    .ttd-nama {
        display: inline-block;
        margin-top: -5px;
        margin-left: 20px;
        text-transform: uppercase;
        font-weight: bold;
    }

    .catatan {
        margin-top: 10px;
        text-align: justify;
    }

    .nama_deskripsi {
        margin-left: 109px;
    }

    .induk_deskripsi {
        margin-left: 72px;
    }

    .jabatan_deskripsi {
        margin-left: 102px;
    }

    .alamat_deskripsi {
        margin-left: 102px;
    }

    .nama_informasi {
        margin-left: 105px
    }

    .tgl_informasi {
        margin-left: 59px;
    }

    .ybs_informasi {
        margin-left: 25px;
    }

    .jenis_informasi {
        margin-left: 49px;
    }

    .kelas_informasi {
        margin-left: 39px;
    }

    .rumah_informasi {
        margin-left: 29px;
    }

    .list {
        font-size: 12px;
    }

    .stempel {
        display: block;
        margin-left: -260px;
        margin-top: 10px;
        margin-bottom: 20px;
    }

    ol {
        margin-left: -25px;
    }

</style>

<body>
    <img src="{{ public_path('vendor/lakers/img/logo-pjb.png') }}" alt="" height="41" width="150">
    <div class="judul_surat" align="center">
        <h3 class="header">SURAT JAMINAN PERAWATAN KESEHATAN</h3>
        <h3 class="nomor">No. : {{ $formjaminan['nomor_surat'] }}</h3>
    </div>
    <div class="pembuka_surat">
        <p class="pertama" style="text-align: justify">
            Yang bertanda tangan di bawah ini , Senior Manager <strong> PT PLN Nusantara Power Unit Pembangkitan Muara Karang</strong> dengan ini menerangkan bahwa,
        </p>
    </div>
    <div class="deskripsi" style="font-size: 12px;">
        <span>Nama <span class="nama_deskripsi">:</span> </span>
        <span class="nama">{{ $formjaminan['karyawan']['nama_karyawan'] }}</span>
        <br>
        <span>Nomor Induk <span class="induk_deskripsi">:</span> </span>
        <span>{{ $formjaminan['karyawan']['nid'] }}</span>
        <br>
        <span>Jabatan <span class="jabatan_deskripsi">:</span> </span>
        <span class="jabatan">{{ $formjaminan['karyawan']['jabatan'] }}</span>
        <br>
        <span>Alamat <span class="alamat_deskripsi">:</span> </span>
        <span class="alamat">{{ $formjaminan['karyawan']['alamat'] }}</span>
    </div>
    <div class="isi_surat">
        <p class="pertama" style="text-align: justify">
            Adalah benar yang bersangkutan karyawan <strong>PT PLN Nusantara Power Unit Pembangkitan Muara Karang</strong>, Surat jaminan perawatan
            kesehatan ini di berikan kepada karyawan yang bersangkutan untuk keperluan perawatan sebagai berikut :
        </p>
    </div>
    <div class="informasi_pasien">

        <span>Nama <span class="nama_informasi">:</span> </span>
        <span class="nama">{{ $formjaminan['nama_pasien'] }}</span>
        <br>
        @if ($formjaminan['hubungan_keluarga'] == 'Ybs')
            <span>Tanggal Lahir<span class="tgl_informasi"> :</span> </span>
            <span>{{ $formjaminan['karyawan']['tanggal_lahir'] }}</span>
        @elseif($formjaminan['hubungan_keluarga']== 'Istri / Suami')
            <span>Tanggal Lahir<span class="tgl_informasi"> :</span> </span>
            <span>{{ $formjaminan['karyawan']['tgl_lahir_istri'] }}</span>
        @elseif($formjaminan['hubungan_keluarga']== 'Anak Ke 1')
            <span>Tanggal Lahir<span class="tgl_informasi"> :</span> </span>
            <span>{{ $formjaminan['karyawan']['tgl_lahir_anak_1'] }}</span>
        @elseif($formjaminan['hubungan_keluarga']== 'Anak Ke 2')
            <span>Tanggal Lahir<span class="tgl_informasi"> :</span> </span>
            <span>{{ $formjaminan['karyawan']['tgl_lahir_anak_2'] }}</span>
        @else
            <span>Tanggal Lahir<span class="tgl_informasi"> :</span> </span>
            <span>{{ $formjaminan['karyawan']['tgl_lahir_anak_3'] }}</span>
        @endif
        <br>
        <span>Hubungan Keluarga <span class="ybs_informasi">:</span> </span>
        <span>{{ $formjaminan['hubungan_keluarga'] }}</span>
        <br>
        <span>Jenis Pelayanan <span class="jenis_informasi">:</span> </span>
        <span>{{ $formjaminan['jenisPemeriksaan']['jenis_pemeriksaan'] }}</span>
        <br>
        <span>Kelas Rawat Inap <span class="kelas_informasi">:</span> </span>
        <span>{{ $formjaminan['karyawan']['kelasRawatInap']['jenis_kelas'] }} / Rp.
            {{ number_format($formjaminan['karyawan']['kelasRawatInap']['harga']) }}</span>
        <br>
        <span>Nama Rumah Sakit <span class="rumah_informasi">:</span> </span>
        <span class="rumah_sakit">{{ $formjaminan['rumahSakit']['nama_rumah_sakit'] }}</span>
    </div>
    <div class="penjelasan">
        <p>Semua biaya dapat ditagihkan di <strong>PT PLN Nusantara Power Unit Pembangkitan Muara Karang</strong>, Jalan Pluit Karang
            Ayu Jakarta Utara-14450, sedangkan selisih biaya yang tidak sesuai haknya dan non medis serta biaya materai
            ditanggung oleh Karyawan sebelum meninggalkan Rumah Sakit.</p>
        <p>Untuk koordinasi terkait diagnosa dan rencana tindakan yang akan di berikan kepada pasien, dapat menghubungi Dokter Perusahaan, yaitu dr. Fahmizar Satria (0812-2179-4775). untuk informasi lebih lanjut terkait administrasi jaminan perawatan kesehatan, dapat menghubungi contact person : Sdr . I Gusti Ngurah Bartha ( HP : 082230920005) dan juga dapat melakukan koordinasi kepada Assistant Manager SDM, Umum & CSR UP Muara Karang (Ibu Sarika Apriyeni Gopar).</p>
        <p>Demikian atas bantuan dan kerja sama yang baik, kami ucapkan terima kasih.</p>


    </div>
    <div class="ttd">
        <span> Jakarta, {{ date('j F Y') }}</span>
        <br>
        @if ($formjaminan['rangking'] == 3)
        <span class="ttd-mkad" style="padding-bottom:70px; padding-top:10px;">
            {{ $sm[0]['jabatan'] }}
        </span>
        <br>
 
        <span class="ttd-nama">{{ $sm[0]['name'] }}</span>
       
        <div class=">paraf" style="margin-top:-30px;">
            <img src="{{public_path('qrcode/asman.png')}}" alt="" width="30">
        </div>
        @endif

        @if ($formjaminan['rangking'] != 3)
        <span class="ttd-mkad" style="padding-bottom:70px; padding-top:10px;">
            {{ $sm[0]['jabatan'] }}
        </span>
        <br>
        <div class="paraf">
            <img src="{{public_path('qrcode/mkad.png')}}" alt="" width="30">
        </div>
        <span class="ttd-nama" style="margin-top:-25px;">{{ $sm[0]['name'] }}</span>
        <div class=">paraf" style="margin-top:-30px;">
            <img src="{{public_path('qrcode/asman.png')}}" alt="" width="30">
        </div>
        @endif

    </div>
    <div class="catatan">
        <span style="text-decoration: underline;">Catatan</span>
        <br>
        <span>Surat Tagihan agar dilampiri : </span>
        <ol type="a" class="list">
            <li>Kuitansi atas nama PT PLN Nusantara Power Unit Pembangkitan Muara Karang.</li>
            <li>Daftar nama obat yang di pakai.</li>
            <li>Fotokopi surat pengantar penegakan diagnosa (bila ada).</li>
            <li>Surat Pengantaar / Jaminan Aslinya.</li>
            <li>Foto Copy daftar tarif kamar rawat inap.</li>
            <li>Apabila ybs mengambil Kelas Rawat Inap di atas haknya, mohon melampirkan rincian selisih seluruh biaya
                rawat inap.</li>
            <li>Apabula Kelas Rawat Inap yang menjadi hak karyawan tidak tersedia karena penuh, Karyawan boleh mengambil
                Kelas Rawat Inap yang lebih tinggi sampai tersedia kelas yang sesuai haknya. Menjadi pihak Rumah Sakit
                <strong style="color:red;">melampirkan surat keterangan yang menyatakan Kelas Rawat Inap yang menjadi
                    hak Karyawan tidak
                    tersedia</strong>.
            </li>
            <li><strong style="color:red;">Melampirkan Surat Rujukan Untuk Rawat Inap</strong>.</li>
        </ol>
    </div>

</body>

</html>
