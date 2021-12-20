<!-- Field Id Field -->
<div class="form-group">
    {!! Form::label('field_id', __('models/polygons.fields.field_id').':') !!}
    <p>{{ $polygon->field_id }}</p>
</div>

<!-- Geometry Field -->
<div class="form-group">
    {!! Form::label('geometry', __('models/polygons.fields.geometry').':') !!}
    <p>{{ $polygon->geometry }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', __('models/polygons.fields.created_at').':') !!}
    <p>{{ $polygon->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', __('models/polygons.fields.updated_at').':') !!}
    <p>{{ $polygon->updated_at }}</p>
</div>

