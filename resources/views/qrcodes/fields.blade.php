<!-- Point Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('point_id', __('models/qrcodes.fields.point_id').':') !!}
    {!! Form::select('point_id', [], null, ['class' => 'form-control']) !!}
</div>

<!-- Content Field -->
<div class="form-group col-sm-6">
    {!! Form::label('content', __('models/qrcodes.fields.content').':') !!}
    {!! Form::text('content', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(__('crud.save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('qrcodes.index') }}" class="btn btn-light">@lang('crud.cancel')</a>
</div>
