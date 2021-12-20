<!-- Date Field -->
<div class="form-group">
    {!! Form::label('date', __('models/trips.fields.date').':') !!}
    <p>{{ $trip->date }}</p>
</div>

<!-- Status Field -->
<div class="form-group">
    {!! Form::label('status', __('models/trips.fields.status').':') !!}
    <p>{{ $trip->status }}</p>
</div>

<!-- Field Id Field -->
<div class="form-group">
    {!! Form::label('field_id', __('models/trips.fields.field_id').':') !!}
    <p>{{ $trip->field_id }}</p>
</div>

<!-- Date Completed Field -->
<div class="form-group">
    {!! Form::label('date_completed', __('models/trips.fields.date_completed').':') !!}
    <p>{{ $trip->date_completed }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', __('models/trips.fields.created_at').':') !!}
    <p>{{ $trip->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', __('models/trips.fields.updated_at').':') !!}
    <p>{{ $trip->updated_at }}</p>
</div>

