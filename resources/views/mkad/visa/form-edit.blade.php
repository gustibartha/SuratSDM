<div class="form-row">
    <div class="col-md-12">
        <div class="form-group{{ $errors->has('karyawan_id') ? 'has-error' : '' }}">
            {!! Form::label('karyawan_id', 'Karyawan', ['class' => 'control-label']) !!}
            <br>
            <input type="hidden" name="jenis_surat" value="personal">
            <select name="karyawan_id" id="karyawan_id_personal" class="js-example-basic-multiple form-control">
                <option value="" disabled selected>Silahkan Dipilih</option>
                @foreach ($karyawans as $item)
                    <option value="{{ $item->id }}">{{ $item->nama_karyawan }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group{{ $errors->has('riwayat_penyakit') ? 'has-error' : '' }}">
            {!! Form::label('riwayat_penyakit', 'Riwayat Penyakit', ['class' => 'control-label']) !!}
            {!! Form::textarea('riwayat_penyakit', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('riwayat_penyakit', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('jenis_pengobatan') ? 'has-error' : '' }}">
            {!! Form::label('jenis_pengobatan', 'Jenis Pengobatan', ['class' => 'control-label']) !!}
            {!! Form::textarea('jenis_pengobatan', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('jenis_pengobatan', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('riwayat_obat') ? 'has-error' : '' }}">
            {!! Form::label('riwayat_obat', 'Riwayat Obat', ['class' => 'control-label']) !!}
            {!! Form::textarea('riwayat_obat', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('riwayat_obat', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('resume_medis') ? 'has-error' : '' }}">
            {!! Form::label('resume_medis', 'Resume Medis', ['class' => 'control-label']) !!}
            {!! Form::textarea('resume_medis', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('resume_medis', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

<div class="form-group" align="right">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-success btn-lg']) !!}
    <a href="#" onClick="javascript:history.go(-1)" class="btn btn-danger btn-lg">Cancel and Back</a>
</div>
