<!-- Subpolygon Id Field -->
<div class="form-group">
    {!! Form::label('subpolygon_id', __('models/points.fields.subpolygon_id').':') !!}
    <p>{{ $point->subpolygon_id }}</p>
</div>

<!-- Lat Field -->
<div class="form-group">
    {!! Form::label('lat', __('models/points.fields.lat').':') !!}
    <p>{{ $point->lat }}</p>
</div>

<!-- Lon Field -->
<div class="form-group">
    {!! Form::label('lon', __('models/points.fields.lon').':') !!}
    <p>{{ $point->lon }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', __('models/points.fields.created_at').':') !!}
    <p>{{ $point->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', __('models/points.fields.updated_at').':') !!}
    <p>{{ $point->updated_at }}</p>
</div>

