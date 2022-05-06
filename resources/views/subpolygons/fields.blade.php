<!-- Polygon Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('polygon_id', __('models/subpolygons.fields.polygon_id').':') !!}
    {!! Form::text('polygon_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Geometry Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('geometry', __('models/subpolygons.fields.geometry').':') !!}
    {!! Form::textarea('geometry', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(__('crud.save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('subpolygons.index') }}" class="btn btn-light">@lang('crud.cancel')</a>
</div>
