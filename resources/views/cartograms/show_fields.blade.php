<!-- Field Id Field -->
<div class="form-group">
    {!! Form::label('field_id', __('models/cartograms.fields.field_id').':') !!}
    <p>{{ $cartogram->field_id }}</p>
</div>

<!-- Status Field -->
<div class="form-group">
    {!! Form::label('status', __('models/cartograms.fields.status').':') !!}
    <p>{{ $cartogram->status }}</p>
</div>

<!-- Access Url Field -->
<div class="form-group">
    {!! Form::label('access_url', __('models/cartograms.fields.access_url').':') !!}
    <p>{{ $cartogram->access_url }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', __('models/cartograms.fields.created_at').':') !!}
    <p>{{ $cartogram->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', __('models/cartograms.fields.updated_at').':') !!}
    <p>{{ $cartogram->updated_at }}</p>
</div>

