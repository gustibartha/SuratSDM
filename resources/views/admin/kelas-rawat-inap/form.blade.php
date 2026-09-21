<div class="form-group{{ $errors->has('jenis_kelas') ? 'has-error' : '' }}">
    {!! Form::label('jenis_kelas', 'Jenis Kelas', ['class' => 'control-label']) !!}
    {!! Form::text('jenis_kelas', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('jenis_kelas', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('harga') ? 'has-error' : '' }}">
    {!! Form::label('harga', 'Harga', ['class' => 'control-label']) !!}
    {!! Form::number('harga', null, '' == 'required' ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
    {!! $errors->first('harga', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group" align="right">
    {!! Form::submit($formMode === 'edit' ? 'Update' : 'Create', ['class' => 'btn btn-success btn-lg']) !!}
    <a href="#" onClick="javascript:history.go(-1)" class="btn btn-danger btn-lg">Cancel and Back</a>
</div>
