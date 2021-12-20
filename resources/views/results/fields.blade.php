<!-- Sample Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('sample_id', __('models/results.fields.sample_id').':') !!}
    {!! Form::select('sample_id', \App\Models\Sample::dropdownForResult(), null, ['class' => 'form-control']) !!}
</div>

<!-- Passed Field -->
<div class="form-group col-sm-6">
    {!! Form::label('passed', __('models/results.fields.passed').':') !!}
    {!! Form::text('passed', null, ['class' => 'form-control']) !!}
</div>

<!-- Accepted Field -->
<div class="form-group col-sm-6">
    {!! Form::label('accepted', __('models/results.fields.accepted').':') !!}
    {!! Form::text('accepted', null, ['class' => 'form-control']) !!}
</div>

<!-- humus Field -->
<div class="form-group col-sm-6">
    {!! Form::label('humus', __('models/results.fields.humus').':') !!}
    {!! Form::text('humus', null, ['class' => 'form-control']) !!}
</div>

<!-- ph Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ph', __('models/results.fields.ph').':') !!}
    {!! Form::text('ph', null, ['class' => 'form-control']) !!}
</div>

<!-- no3 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('no3', __('models/results.fields.no3').':') !!}
    {!! Form::text('no3', null, ['class' => 'form-control']) !!}
</div>

<!-- p Field -->
<div class="form-group col-sm-6">
    {!! Form::label('p', __('models/results.fields.p').':') !!}
    {!! Form::text('p', null, ['class' => 'form-control']) !!}
</div>

<!-- k Field -->
<div class="form-group col-sm-6">
    {!! Form::label('k', __('models/results.fields.k').':') !!}
    {!! Form::text('k', null, ['class' => 'form-control']) !!}
</div>

<!-- s Field -->
<div class="form-group col-sm-6">
    {!! Form::label('s', __('models/results.fields.s').':') !!}
    {!! Form::text('s', null, ['class' => 'form-control']) !!}
</div>



<!-- b Field -->
<div class="form-group col-sm-6">
    {!! Form::label('b', __('models/results.fields.b').':') !!}
    {!! Form::text('b', $mode != null ? 0 : null, ['class' => 'form-control']) !!}
</div>

<!-- fe Field -->
<div class="form-group col-sm-6">
    {!! Form::label('fe', __('models/results.fields.fe').':') !!}
    {!! Form::text('fe', $mode != null ? 0 : null, ['class' => 'form-control']) !!}
</div>

<!-- mn Field -->
<div class="form-group col-sm-6">
    {!! Form::label('mn', __('models/results.fields.mn').':') !!}
    {!! Form::text('mn', $mode != null ? 0 : null, ['class' => 'form-control']) !!}
</div>

<!-- cu Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cu', __('models/results.fields.cu').':') !!}
    {!! Form::text('cu', $mode != null ? 0 : null, ['class' => 'form-control']) !!}
</div>

<!-- zn Field -->
<div class="form-group col-sm-6">
    {!! Form::label('zn', __('models/results.fields.zn').':') !!}
    {!! Form::text('zn', $mode != null ? 0 : null, ['class' => 'form-control']) !!}
</div>

<!-- na Field -->
<div class="form-group col-sm-6">
    {!! Form::label('na', __('models/results.fields.na').':') !!}
    {!! Form::text('na', $mode != null ? 0 : null, ['class' => 'form-control']) !!}
</div>

<!-- calcium Field -->
<div class="form-group col-sm-6">
    {!! Form::label('calcium', __('models/results.fields.calcium').':') !!}
    {!! Form::text('calcium', $mode != null ? 0 : null, ['class' => 'form-control']) !!}
</div>

<!-- magnesium Field -->
<div class="form-group col-sm-6">
    {!! Form::label('magnesium', __('models/results.fields.magnesium').':') !!}
    {!! Form::text('magnesium', $mode != null ? 0 : null, ['class' => 'form-control']) !!}
</div>

<!-- salinity Field -->
<div class="form-group col-sm-6">
    {!! Form::label('salinity', __('models/results.fields.salinity').':') !!}
    {!! Form::text('salinity', $mode != null ? 0 : null, ['class' => 'form-control']) !!}
</div>

<!-- absorbed_sum Field -->
<div class="form-group col-sm-6">
    {!! Form::label('absorbed_sum', __('models/results.fields.absorbed_sum').':') !!}
    {!! Form::text('absorbed_sum', $mode != null ? 0 : null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(__('crud.save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('results.index') }}" class="btn btn-light">@lang('crud.cancel')</a>
</div>
