<div class="row">
    <div class="col-6">
        {{-- <div class="form-group{{ $errors->has('nomor_surat') ? 'has-error' : '' }}">
            {!! Form::label('nomor_surat', 'Nomor Surat', ['class' => 'control-label']) !!}
            <input type="hidden" name="jenis_surat" value="personal">
            {!! Form::text('nomor_surat', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('nomor_surat', '<p class="help-block">:message</p>') !!}
        </div> --}}

        <div class="form-group{{ $errors->has('id_karyawan') ? 'has-error' : '' }}">
            {!! Form::label('id_karyawan', 'Karyawan', ['class' => 'control-label']) !!}
            <input type="hidden" name="jenis_surat" value="keluarga">
            <select name="id_karyawan" id="id_karyawan_keluarga" class="js-example-basic-multiple-keluarga form-control">
                <option value="" disabled selected>Silahkan Dipilih</option>
                @foreach ($karyawan as $item)
                    <option value="{{ $item->id }}">{{ $item->nama_karyawan }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group{{ $errors->has('id_kelas_rawat_inap') ? 'has-error' : '' }}">
            {!! Form::label('id_kelas_rawat_inap', 'Kelas Rawat Inap', ['class' => 'control-label']) !!}
            <input class="form-control" type="text" name="id_kelas_rawat_inap" id="id_kelas_rawat_inap_keluarga"
                value="" readonly>
            {!! $errors->first('id_kelas_rawat_inap', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('nama_pasien') ? 'has-error' : '' }}">
            {!! Form::label('nama_pasien', 'Nama Pasien', ['class' => 'control-label']) !!}
            <select name="nama_pasien" id="nama_pasien" class="form-control">
                <option id="nama_pasien1"></option>
                <option id="nama_pasien2"></option>
                <option id="nama_pasien3"></option>
                <option id="nama_pasien4"></option>
            </select>
            {!! $errors->first('nama_pasien', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-6">
        <div class="form-group{{ $errors->has('id_jenis_pemeriksaan') ? 'has-error' : '' }}">
            {!! Form::label('id_jenis_pemeriksaan', 'Jenis Pemeriksaan', ['class' => 'control-label']) !!}
            {!! Form::select('id_jenis_pemeriksaan', $jenisPemeriksaan, null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control', 'placeholder' => 'Silahkan Pilih']) !!}
            {!! $errors->first('id_jenis_pemeriksaan', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('id_rumah_sakit') ? 'has-error' : '' }}">
            {!! Form::label('id_rumah_sakit', 'Rumah Sakit', ['class' => 'control-label']) !!}
            <select name="id_rumah_sakit" id="id_rumah_sakit_keluarga" class="js-example-basic-multiple-keluarga form-control">
                <option value="" disabled selected>Silahkan Dipilih</option>
                @foreach ($rumahSakit as $item)
                    <option value="{{ $item->id }}">{{ $item->nama_rumah_sakit }}</option>
                @endforeach
            </select>
            {{-- {!! Form::select('id_rumah_sakit', $rumahSakit, null, '' == 'required' ? ['class' => 'js-example-basic-multiple form-control', 'required' => 'required'] : ['class' => 'js-example-basic-multiple form-control', 'placeholder' => 'Silahkan Pilih']) !!} --}}
            {!! $errors->first('id_rumah_sakit', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('hubungan_keluarga') ? 'has-error' : '' }}">
            {!! Form::label('hubungan_keluarga', 'Hubungan Keluarga', ['class' => 'control-label']) !!}
            <select name="hubungan_keluarga" class="form-control">
                <option>Istri / Suami</option>
                <option>Anak Ke 1</option>
                <option>Anak Ke 2</option>
                <option>Anak Ke 3</option>
            </select>
            {!! $errors->first('nama_pasien', '<p class="help-block">:message</p>') !!}
        </div>

        {{-- <div class="form-group{{ $errors->has('biaya_rumah_sakit') ? 'has-error' : '' }}">
            {!! Form::label('biaya_rumah_sakit', 'Biaya Rumah Sakit', ['class' => 'control-label']) !!}
            {!! Form::number('biaya_rumah_sakit', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('biaya_rumah_sakit', '<p class="help-block">:message</p>') !!}
        </div> --}}
        {{-- <div class="form-group{{ $errors->has('status_pengajuan') ? 'has-error' : ''}}">
    {!! Form::label('status_pengajuan', 'Status Pengajuan', ['class' => 'control-label']) !!}
    {!! Form::text('status_pengajuan', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('status_pengajuan', '<p class="help-block">:message</p>') !!}
</div> --}}
        {{-- <div class="form-group{{ $errors->has('status_email') ? 'has-error' : ''}}">
    {!! Form::label('status_email', 'Status Email', ['class' => 'control-label']) !!}
    <div class="checkbox">
    <label>{!! Form::radio('%1$s', '1') !!} Yes</label>
</div> --}}
    </div>
</div>


<div class="form-group" align="right">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-success btn-lg']) !!}
    <a href="#" class="btn btn-danger btn-lg" data-dismiss="modal">Cancel and Back</a>
</div>
