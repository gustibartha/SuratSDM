<div class="form-group{{ $errors->has('jenis_pemeriksaan') ? 'has-error' : ''}}">
    {!! Form::label('jenis_pemeriksaan', 'Jenis Pemeriksaan', ['class' => 'control-label']) !!}
    {!! Form::text('jenis_pemeriksaan', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('jenis_pemeriksaan', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('keterangan') ? 'has-error' : ''}}">
    {!! Form::label('keterangan', 'Keterangan', ['class' => 'control-label']) !!}
    {!! Form::textarea('keterangan', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('keterangan', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group" align="right">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-success btn-lg']) !!}
    <a href="#" onClick="javascript:history.go(-1)" class="btn btn-danger btn-lg">Cancel and Back</a>
</div>
