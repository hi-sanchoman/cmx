<!-- Date Started Field -->
<div class="form-group">
    {!! Form::label('date_started', __('models/paths.fields.date_started').':') !!}
    <p>{{ $path->date_started }}</p>
</div>

<!-- Date Completed Field -->
<div class="form-group">
    {!! Form::label('date_completed', __('models/paths.fields.date_completed').':') !!}
    <p>{{ $path->date_completed }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', __('models/paths.fields.created_at').':') !!}
    <p>{{ $path->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', __('models/paths.fields.updated_at').':') !!}
    <p>{{ $path->updated_at }}</p>
</div>

