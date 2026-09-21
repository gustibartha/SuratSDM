<div class="row">
    <div class="col-6">
        @if ($formMode != 'edit')
            <div class="form-group{{ $errors->has('id_form_jaminan') ? 'has-error' : '' }}">
                {!! Form::label('id_form_jaminan', 'Nomor Form Jaminan', ['class' => 'control-label']) !!}
                {!! Form::select('id_form_jaminan', $formjaminan, null, '' == 'required' ? ['class' => 'form-control js-example-basic-multiple', 'required' => 'required'] : ['class' => 'form-control js-example-basic-multiple']) !!}
                {!! $errors->first('id_form_jaminan', '<p class="help-block">:message</p>') !!}
            </div>
        @endif
        <div class="form-group{{ $errors->has('tanggal_tagihan') ? 'has-error' : '' }}">
            {!! Form::label('tanggal_tagihan', 'Tanggal Tagihan', ['class' => 'control-label']) !!}
            {!! Form::date('tanggal_tagihan', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('tanggal_tagihan', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('no_tagihan') ? 'has-error' : '' }}">
            {!! Form::label('no_tagihan', 'No Tagihan', ['class' => 'control-label']) !!}
            {!! Form::text('no_tagihan', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('no_tagihan', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('jumlah') ? 'has-error' : '' }}">
            {!! Form::label('jumlah', 'Jumlah', ['class' => 'control-label']) !!}
            {!! Form::number('jumlah', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('jumlah', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('tanggal_pembayaran') ? 'has-error' : '' }}">
            {!! Form::label('tanggal_pembayaran', 'Tanggal Pembayaran', ['class' => 'control-label']) !!}
            {!! Form::date('tanggal_pembayaran', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('tanggal_pembayaran', '<p class="help-block">:message</p>') !!}
        </div>

    </div>
    <div class="col-6">
        <div class="form-group{{ $errors->has('tanggal_realisasi_perawatan') ? 'has-error' : '' }}">
            {!! Form::label('tanggal_realisasi_perawatan', 'Tanggal Realisasi Perawatan', ['class' => 'control-label']) !!}
            {!! Form::date('tanggal_realisasi_perawatan', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('tanggal_realisasi_perawatan', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('tanggal_realisasi_perawatan_akhir') ? 'has-error' : '' }}">
            {!! Form::label('tanggal_realisasi_perawatan_akhir', 'Tanggal Realisasi Perawatan Akhir', ['class' => 'control-label']) !!}
            {!! Form::date('tanggal_realisasi_perawatan_akhir', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('tanggal_realisasi_perawatan_akhir', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('keterangan') ? 'has-error' : '' }}">
            {!! Form::label('keterangan', 'Keterangan', ['class' => 'control-label']) !!}
            {!! Form::textarea('keterangan', null, '' == 'required' ? ['class' => ['form-control', 'h-50'], 'required' => 'required'] : ['class' => 'form-control']) !!}
            {!! $errors->first('keterangan', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group{{ $errors->has('status_pembayaran') ? 'has-error' : '' }}">
            {!! Form::label('status_pembayaran', 'Status Pembayaran', ['class' => 'control-label']) !!}
            <select name="status_pembayaran" class="form-control">
                <option value="Belum Di Bayar">Belum Di Bayar</option>
                <option value="Sudah Di Bayar">Sudah Di Bayar</option>
            </select>
            {!! $errors->first('nama_pasien', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>




<div class="form-group" align="right">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-success btn-lg']) !!}
    <a href="#" onClick="javascript:history.go(-1)" class="btn btn-danger btn-lg">Cancel and Back</a>
</div>
