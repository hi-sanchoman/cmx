<!-- Cartogram Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cartogram_id', __('models/protocols.fields.cartogram_id').':') !!}
    {!! Form::select('cartogram_id', [], null, ['class' => 'form-control']) !!}
</div>

<!-- Path Field -->
<div class="form-group col-sm-6">
    {!! Form::label('path', __('models/protocols.fields.path').':') !!}
    {!! Form::text('path', null, ['class' => 'form-control']) !!}
</div>

<!-- Access Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('access_url', __('models/protocols.fields.access_url').':') !!}
    {!! Form::text('access_url', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(__('crud.save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('protocols.index') }}" class="btn btn-light">@lang('crud.cancel')</a>
</div>
