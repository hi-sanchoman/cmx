<!-- Point Id Field -->
<div class="form-group">
    {!! Form::label('point_id', __('models/samples.fields.point_id').':') !!}
    <p>{{ $sample->point_id }}</p>
</div>

<!-- Date Selected Field -->
<div class="form-group">
    {!! Form::label('date_selected', __('models/samples.fields.date_selected').':') !!}
    <p>{{ $sample->date_selected }}</p>
</div>

<!-- Date Received Field -->
<div class="form-group">
    {!! Form::label('date_received', __('models/samples.fields.date_received').':') !!}
    <p>{{ $sample->date_received }}</p>
</div>

<!-- Quantity Field -->
<div class="form-group">
    {!! Form::label('quantity', __('models/samples.fields.quantity').':') !!}
    <p>{{ $sample->quantity }}</p>
</div>

<!-- Passed Field -->
<div class="form-group">
    {!! Form::label('passed', __('models/samples.fields.passed').':') !!}
    <p>{{ $sample->passed }}</p>
</div>

<!-- Accepted Field -->
<div class="form-group">
    {!! Form::label('accepted', __('models/samples.fields.accepted').':') !!}
    <p>{{ $sample->accepted }}</p>
</div>

<!-- Notes Field -->
<div class="form-group">
    {!! Form::label('notes', __('models/samples.fields.notes').':') !!}
    <p>{{ $sample->notes }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', __('models/samples.fields.created_at').':') !!}
    <p>{{ $sample->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', __('models/samples.fields.updated_at').':') !!}
    <p>{{ $sample->updated_at }}</p>
</div>

