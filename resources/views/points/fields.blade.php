<!-- Num Field -->
<div class="form-group col-sm-6">
    {!! Form::label('num', __('models/points.fields.num').':') !!}
    {!! Form::text('num', null, ['class' => 'form-control']) !!}
</div>

<!-- Lat Field -->
<div class="form-group col-sm-6">
    {!! Form::label('lat', __('models/points.fields.lat').':') !!}
    {!! Form::text('lat', null, ['class' => 'form-control']) !!}
</div>

<!-- Lon Field -->
<div class="form-group col-sm-6">
    {!! Form::label('lon', __('models/points.fields.lon').':') !!}
    {!! Form::text('lon', null, ['class' => 'form-control']) !!}
</div>

@if (isset($ref) && $ref == 'fields_show')
    {!! Form::hidden('ref', $ref) !!}
    {!! Form::hidden('field_id', $fieldId) !!}
    {!! Form::hidden('polygon_id', $polygon->id) !!}
@endif

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(__('crud.save'), ['class' => 'btn btn-primary']) !!}
    <!-- <a href="{{ route('points.index') }}" class="btn btn-light">@lang('crud.cancel')</a> -->
</div>
