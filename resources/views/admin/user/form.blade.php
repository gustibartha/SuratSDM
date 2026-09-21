<div class="form-group{{ $errors->has('nid') ? 'has-error' : '' }}">
    <label for="nid" class="control-label">{{ 'Nid' }}</label>
    <input class="form-control" name="nid" type="text" id="nid" value="{{ $user->nid or '' }}">
    {!! $errors->first('nid', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('jabatan') ? 'has-error' : '' }}">
    <label for="jabatan" class="control-label">{{ 'Jabatan' }}</label>
    <input class="form-control" name="jabatan" type="text" id="jabatan" value="{{ $user->jabatan or '' }}">
    {!! $errors->first('jabatan', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('name') ? 'has-error' : '' }}">
    <label for="name" class="control-label">{{ 'Name' }}</label>
    <input class="form-control" name="name" type="text" id="name" value="{{ $user->name or '' }}">
    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('email') ? 'has-error' : '' }}">
    <label for="email" class="control-label">{{ 'Email' }}</label>
    <input class="form-control" name="email" type="email" id="email" value="{{ $user->email or '' }}">
    {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
</div>
<div class="form-group{{ $errors->has('role') ? 'has-error' : '' }}">
    <label for="role" class="control-label">{{ 'Role' }}</label>
    <select name="role" class="form-control" id="role">
        @foreach (json_decode('{" mkad":"mkad","sm":"sm" ,"admin":"admin","dokter":"dokter","asman":"asman"}', true) as $optionKey=>
            $optionValue)
            <option value="{{ $optionKey }}"
                {{ isset($user->role) && $user->role == $optionKey ? 'selected' : '' }}>{{ $optionValue }}
            </option>
        @endforeach
    </select>
    {!! $errors->first('role', '<p class="help-block">:message</p>') !!}
</div>
@if ($user['role'] == 'sm')
    <div class="form-group{{ $errors->has('file_ttd') ? 'has-error' : '' }}">
        <label for="file_ttd" class="control-label">{{ 'File Tanda Tangan dan Stampel' }}</label>
        <input class="form-control" name="file_ttd" type="file" id="file_ttd" value="{{ $user->file_ttd or '' }}">
        {!! $errors->first('file_ttd', '<p class="help-block">:message</p>') !!}
        <small>File berformat Png dan maksimal file 4MB</small>
    </div>
    @if ($user['file_ttd'])
        <div>
            <img src="{{ asset('ttd_file/' . $user['file_ttd']) }}" alt="" height="75" width="150">
        </div>
    @endif

@endif
@if ($user['role'] == 'mkad')
    <div class="form-group{{ $errors->has('file_ttd') ? 'has-error' : '' }}">
        <label for="file_ttd" class="control-label">{{ 'File Paraf' }}</label>
        <input class="form-control" name="file_ttd" type="file" id="file_ttd" value="{{ $user->file_ttd or '' }}">
        {!! $errors->first('file_ttd', '<p class="help-block">:message</p>') !!}
        <small>File berformat Png dan maksimal file 4MB</small>
    </div>
    @if ($user['file_ttd'])
        <div>
            <img src="{{ asset('ttd_file/' . $user['file_ttd']) }}" alt="" height="75" width="150">
        </div>
    @endif

@endif
<div class="form-group{{ $errors->has('password') ? 'has-error' : '' }}">
    <label for="password" class="control-label">{{ 'Password' }}</label>
    <input class="form-control" name="password" type="password" id="password">
    {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
</div>


<div class="form-group">
    <input class="btn btn-theme ctm-border-radius text-white float-right button-1 ml-3" type="submit"
        value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
    <button type="button"
        class="btn btn-danger text-white ctm-border-radius float-right ml-3" data-dismiss="modal">Cancel</button>
</div>
