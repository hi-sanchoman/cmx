<!-- Field Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('field_id', __('models/cartograms.fields.field_id').':') !!}
    {!! Form::select('field_id', [], null, ['class' => 'form-control']) !!}
</div>

<!-- Access Url Field -->
<div class="form-group col-sm-6">
    {!! Form::label('access_url', __('models/cartograms.fields.access_url').':') !!}
    {!! Form::text('access_url', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(__('crud.save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('cartograms.index') }}" class="btn btn-light">@lang('crud.cancel')</a>
</div>
