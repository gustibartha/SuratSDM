<div class="form-row">
    <div class="col-md-4">
        <div class="form-group{{ $errors->has('nama_karyawan') ? 'has-error' : '' }}">
            {!! Form::label('nama_karyawan', 'Nama Karyawan', ['class' => 'control-label']) !!}
            {!! Form::text('nama_karyawan', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('nama_karyawan', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('nid') ? 'has-error' : '' }}">
            {!! Form::label('nid', 'NID', ['class' => 'control-label']) !!}
            {!! Form::text('nid', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('nid', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('jabatan') ? 'has-error' : '' }}">
            {!! Form::label('jabatan', 'Jabatan', ['class' => 'control-label']) !!}
            {!! Form::text('jabatan', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('jabatan', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('jenjang_jabatan') ? 'has-error' : '' }}">
            {!! Form::label('jenjang_jabatan', 'Jenjang Jabatan', ['class' => 'control-label']) !!}
            {!! Form::text('jenjang_jabatan', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('jenjang_jabatan', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('alamat') ? 'has-error' : '' }}">
            {!! Form::label('alamat', 'Alamat', ['class' => 'control-label']) !!}
            {!! Form::textarea('alamat', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('alamat', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group{{ $errors->has('tanggal_lahir') ? 'has-error' : '' }}">
            {!! Form::label('tanggal_lahir', 'Tanggal Lahir', ['class' => 'control-label']) !!}
            {!! Form::date('tanggal_lahir', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('tanggal_lahir', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('istri') ? 'has-error' : '' }}">
            {!! Form::label('istri', 'Istri / Suami', ['class' => 'control-label']) !!}
            {!! Form::text('istri', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('istri', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('anak_1') ? 'has-error' : '' }}">
            {!! Form::label('anak_1', 'Anak 1', ['class' => 'control-label']) !!}
            {!! Form::text('anak_1', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('anak_1', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('anak_2') ? 'has-error' : '' }}">
            {!! Form::label('anak_2', 'Anak 2', ['class' => 'control-label']) !!}
            {!! Form::text('anak_2', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('anak_2', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('anak_3') ? 'has-error' : '' }}">
            {!! Form::label('anak_3', 'Anak 3', ['class' => 'control-label']) !!}
            {!! Form::text('anak_3', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('anak_3', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('id_kelas_rawat_inap') ? 'has-error' : '' }}">
            {!! Form::label('id_kelas_rawat_inap', 'Kelas Rawat Inap', ['class' => 'control-label']) !!}
            {!! Form::select('id_kelas_rawat_inap', $kelasRawatInap, null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control', 'placeholder' => 'Silahkan Pilih Kelas Rawat Inap']) !!}
            {!! $errors->first('id_kelas_rawat_inap', '<p class="help-block">:message</p>') !!}
        </div>

        {{-- <div class="form-group{{ $errors->has('status_karyawan') ? 'has-error' : '' }}">
            {!! Form::label('status_karyawan', 'Status Karyawan', ['class' => 'control-label']) !!}
            {!! Form::select('status_karyawan', ['karyawan_tetap' => 'Karyawan Tetap', 'pensiunan' => 'Pensiunan'], ['class' => 'form-control']) !!}
            {!! $errors->first('status_karyawan', '<p class="help-block">:message</p>') !!}
        </div> --}}
    </div>
    <div class="col-md-4">
        <div class="form-group{{ $errors->has('status_karyawan') ? 'has-error' : '' }}">
            {!! Form::label('status_karyawan', 'Status Karyawan', ['class' => 'control-label']) !!}
            {!! Form::select('status_karyawan', ['karyawan_tetap' => 'Karyawan', 'pensiunan' => 'Pensiunan'], null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('status_karyawan', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('tgl_lahir_istri') ? 'has-error' : '' }}">
            {!! Form::label('tgl_lahir_istri', 'Tanggal Lahir Istri', ['class' => 'control-label']) !!}
            {!! Form::date('tgl_lahir_istri', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('tgl_lahir_istri', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('tgl_lahir_anak_1') ? 'has-error' : '' }}">
            {!! Form::label('tgl_lahir_anak_1', 'Tanggal Lahir Anak Ke 1', ['class' => 'control-label']) !!}
            {!! Form::date('tgl_lahir_anak_1', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('tgl_lahir_anak_1', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('tgl_lahir_anak_2') ? 'has-error' : '' }}">
            {!! Form::label('tgl_lahir_anak_2', 'Tanggal Lahir Anak Ke 2', ['class' => 'control-label']) !!}
            {!! Form::date('tgl_lahir_anak_2', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('tgl_lahir_anak_2', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('tgl_lahir_anak_3') ? 'has-error' : '' }}">
            {!! Form::label('tgl_lahir_anak_3', 'Tanggal Lahir Anak Ke 3', ['class' => 'control-label']) !!}
            {!! Form::date('tgl_lahir_anak_3', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('tgl_lahir_anak_3', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('email') ? 'has-error' : '' }}">
            {!! Form::label('email', 'Email', ['class' => 'control-label']) !!}
            {!! Form::text('email', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

<div class="form-group" align="right">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-success btn-lg']) !!}
    <a href="#" onClick="javascript:history.go(-1)" class="btn btn-danger btn-lg">Cancel and Back</a>
</div>
