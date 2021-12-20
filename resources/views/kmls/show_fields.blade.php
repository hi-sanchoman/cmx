<!-- Path Field -->
<div class="form-group">
    {!! Form::label('path', __('models/kmls.fields.path').':') !!}
    <p>{{ $kml->path }}</p>
</div>

<!-- Content Field -->
<div class="form-group">
    {!! Form::label('content', __('models/kmls.fields.content').':') !!}
    <p>{{ $kml->content }}</p>
</div>

<!-- Field Id Field -->
<div class="form-group">
    {!! Form::label('field_id', __('models/kmls.fields.field_id').':') !!}
    <p>{{ $kml->field_id }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', __('models/kmls.fields.created_at').':') !!}
    <p>{{ $kml->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', __('models/kmls.fields.updated_at').':') !!}
    <p>{{ $kml->updated_at }}</p>
</div>

