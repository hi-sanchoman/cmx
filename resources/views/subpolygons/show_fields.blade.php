<!-- Polygon Id Field -->
<div class="form-group">
    {!! Form::label('polygon_id', __('models/subpolygons.fields.polygon_id').':') !!}
    <p>{{ $subpolygon->polygon_id }}</p>
</div>

<!-- Geometry Field -->
<div class="form-group">
    {!! Form::label('geometry', __('models/subpolygons.fields.geometry').':') !!}
    <p>{{ $subpolygon->geometry }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', __('models/subpolygons.fields.created_at').':') !!}
    <p>{{ $subpolygon->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', __('models/subpolygons.fields.updated_at').':') !!}
    <p>{{ $subpolygon->updated_at }}</p>
</div>

