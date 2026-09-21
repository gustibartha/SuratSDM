<div class="form-row">
    <div class="col-md-12">
        <div class="form-group{{ $errors->has('karyawan_id') ? 'has-error' : '' }}">
            {!! Form::label('karyawan_id', 'Karyawan', ['class' => 'control-label']) !!}
            <br>
            <select name="karyawan_id" id="id_karyawan_personal" class="js-example-basic-multiple form-control">
                <option value="" disabled selected>Silahkan Dipilih</option>
                @foreach ($karyawans as $item)
                    <option value="{{ $item->id }}">{{ $item->nama_karyawan }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group{{ $errors->has('sifat') ? 'has-error' : '' }}">
            <label for="sifat" class="control-label">{{ 'Sifat' }}</label>
            <input class="form-control" name="sifat" type="text" id="sifat" value="{{ $suratketerangan->sifat or '' }}" placeholder="ex . Biasa">
            {!! $errors->first('sifat', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('penerima') ? 'has-error' : '' }}">
            <label for="penerima" class="control-label">{{ 'Penerima' }}</label>
            <input class="form-control" name="penerima" type="text" id="penerima" value="{{ $suratketerangan->penerima or '' }}" placeholder="Bank BNI">
            {!! $errors->first('penerima', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('alamat_penerima') ? 'has-error' : '' }}">
            <label for="alamat_penerima" class="control-label">{{ 'Alamat Penerima' }}</label>
            <input class="form-control" name="alamat_penerima" type="text" id="alamat_penerima" value="{{ $suratketerangan->alamat_penerima or '' }}" placeholder="Bank BNI">
            {!! $errors->first('alamat_penerima', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('keperluan') ? 'has-error' : '' }}">
            <label for="keperluan" class="control-label">{{ 'Keperluan' }}</label>
            <input class="form-control" name="keperluan" type="text" id="keperluan" value="{{ $suratketerangan->keperluan or '' }}" placeholder="ex . Administrasi KTA BNI Flexi">
            {!! $errors->first('keperluan', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('tanggal_masuk_karyawan') ? 'has-error' : '' }}">
            <label for="tanggal_masuk_karyawan" class="control-label">{{ 'Tanggal Masuk Karyawan' }}</label>
            <input class="form-control" name="tanggal_masuk_karyawan" type="date" id="tanggal_masuk_karyawan" value="{{ $suratketerangan->tanggal_masuk_karyawan or '' }}">
            {!! $errors->first('tanggal_masuk_karyawan', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

<div class="form-group" align="right">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-success btn-lg']) !!}
    <a href="#" onClick="javascript:history.go(-1)" class="btn btn-danger btn-lg">Cancel and Back</a>
</div>
