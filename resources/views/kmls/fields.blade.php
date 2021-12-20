<!-- Path Field -->
<div class="form-group col-sm-6">
    {!! Form::label('path', __('models/kmls.fields.path').':') !!}
    {!! Form::file('path') !!}
</div>
<div class="clearfix"></div>

<!-- Content Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('content', __('models/kmls.fields.content').':') !!}
    {!! Form::textarea('content', null, ['class' => 'form-control']) !!}
</div>

<!-- Field Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('field_id', __('models/kmls.fields.field_id').':') !!}
    {!! Form::select('field_id', ], null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(__('crud.save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('kmls.index') }}" class="btn btn-light">@lang('crud.cancel')</a>
</div>
