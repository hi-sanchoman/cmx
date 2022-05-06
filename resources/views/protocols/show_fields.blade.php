<!-- Cartogram Id Field -->
<div class="form-group">
    {!! Form::label('cartogram_id', __('models/protocols.fields.cartogram_id').':') !!}
    <p>{{ $protocol->cartogram_id }}</p>
</div>

<!-- Path Field -->
<div class="form-group">
    {!! Form::label('path', __('models/protocols.fields.path').':') !!}
    <p>{{ $protocol->path }}</p>
</div>

<!-- Access Url Field -->
<div class="form-group">
    {!! Form::label('access_url', __('models/protocols.fields.access_url').':') !!}
    <p>{{ $protocol->access_url }}</p>
</div>

<!-- Created At Field -->
<div class="form-group">
    {!! Form::label('created_at', __('models/protocols.fields.created_at').':') !!}
    <p>{{ $protocol->created_at }}</p>
</div>

<!-- Updated At Field -->
<div class="form-group">
    {!! Form::label('updated_at', __('models/protocols.fields.updated_at').':') !!}
    <p>{{ $protocol->updated_at }}</p>
</div>

