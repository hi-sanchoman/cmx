<!-- Point Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('point_id', __('models/samples.fields.point_id').':') !!}
    {!! Form::select('point_id', \App\Models\Point::dropdownForSample(), null, ['class' => 'form-control']) !!}
</div>

<!-- Num Field -->
<div class="form-group col-sm-6">
    {!! Form::label('num', __('models/samples.fields.num').':') !!}
    {!! Form::text('num', null, ['class' => 'form-control']) !!}
</div>

<!-- Date Selected Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date_selected', __('models/samples.fields.date_selected').':') !!}
    {!! Form::date('date_selected', isset($sample) ? $sample->date_selected->format('Y-m-d') : null, ['class' => 'form-control','id'=>'date_selected']) !!}
</div>

@push('scripts')
    <script type="text/javascript">
        $('#date_selected').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false
        })
    </script>
@endpush

<!-- Date Received Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date_received', __('models/samples.fields.date_received').':') !!}
    {!! Form::date('date_received', isset($sample) ? $sample->date_received->format('Y-m-d') : null, ['class' => 'form-control','id'=>'date_received']) !!}
</div>

@push('scripts')
    <script type="text/javascript">
        $('#date_received').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false
        })
    </script>
@endpush

<!-- Date Started Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date_started', __('models/samples.fields.date_started').':') !!}
    {!! Form::date('date_started', isset($sample) ? $sample->date_started->format('Y-m-d') : null, ['class' => 'form-control','id'=>'date_started']) !!}
</div>

@push('scripts')
    <script type="text/javascript">
        $('#date_started').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false
        })
    </script>
@endpush

<!-- Date Completed Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date_completed', __('models/samples.fields.date_completed').':') !!}
    {!! Form::date('date_completed', isset($sample) ? $sample->date_completed->format('Y-m-d') : null, ['class' => 'form-control','id'=>'date_completed']) !!}
</div>

@push('scripts')
    <script type="text/javascript">
        $('#date_completed').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false
        })
    </script>
@endpush

<!-- Quantity Field -->
<div class="form-group col-sm-6">
    {!! Form::label('quantity', __('models/samples.fields.quantity').':') !!}
    {!! Form::text('quantity', null, ['class' => 'form-control']) !!}
</div>

<!-- Passed Field -->
<div class="form-group col-sm-6">
    {!! Form::label('passed', __('models/samples.fields.passed').':') !!}
    {!! Form::text('passed', null, ['class' => 'form-control']) !!}
</div>

<!-- Accepted Field -->
<div class="form-group col-sm-6">
    {!! Form::label('accepted', __('models/samples.fields.accepted').':') !!}
    {!! Form::text('accepted', null, ['class' => 'form-control']) !!}
</div>

<!-- Notes Field -->
<div class="form-group col-sm-6">
    {!! Form::label('notes', __('models/samples.fields.notes').':') !!}
    {!! Form::text('notes', null, ['class' => 'form-control']) !!}
</div>

<div class="col-sm-6"></div>

<div class="col-sm-12">
    <h2>Лабораторные значения</h2>
</div>

<div class="col-sm-12">
    <h4>Основные показатели</h4>
</div>

<!-- humus Field -->
<div class="form-group col-sm-3">
    {!! Form::label('humus', __('models/samples.fields.humus').':') !!}
    {!! Form::text('humus', null, ['class' => 'form-control']) !!}
</div>

<!-- humus_mass Field -->
<div class="form-group col-sm-3">
    {!! Form::label('humus_mass', __('models/samples.fields.humus_mass').':') !!}
    {!! Form::text('humus_mass', 1, ['class' => 'form-control']) !!}
</div>

<!-- P Field -->
<div class="form-group col-sm-6">
    {!! Form::label('p', __('models/samples.fields.p').':') !!}
    {!! Form::text('p', null, ['class' => 'form-control']) !!}
</div>

<!-- K Field -->
<div class="form-group col-sm-6">
    {!! Form::label('k', __('models/samples.fields.k').':') !!}
    {!! Form::text('k', null, ['class' => 'form-control']) !!}
</div>

<!-- S Field -->
<div class="form-group col-sm-6">
    {!! Form::label('s', __('models/samples.fields.s').':') !!}
    {!! Form::text('s', null, ['class' => 'form-control']) !!}
</div>

<!-- NO3 Field -->
<div class="form-group col-sm-6">
    {!! Form::label('no3', __('models/samples.fields.no3').':') !!}
    {!! Form::text('no3', null, ['class' => 'form-control']) !!}
</div>

<!-- pH Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ph', __('models/samples.fields.ph').':') !!}
    {!! Form::text('ph', null, ['class' => 'form-control']) !!}
</div>


<div class="col-sm-12">
    <h4>Остальные показатели</h4>
</div>

<!-- B Field -->
<div class="form-group col-sm-6">
    {!! Form::label('b', __('models/samples.fields.b').':') !!}
    {!! Form::text('b', null, ['class' => 'form-control']) !!}
</div>

<!-- Fe Field -->
<div class="form-group col-sm-6">
    {!! Form::label('fe', __('models/samples.fields.fe').':') !!}
    {!! Form::text('fe', null, ['class' => 'form-control']) !!}
</div>

<!-- mn Field -->
<div class="form-group col-sm-6">
    {!! Form::label('mn', __('models/samples.fields.mn').':') !!}
    {!! Form::text('mn', null, ['class' => 'form-control']) !!}
</div>

<!-- Cu Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cu', __('models/samples.fields.cu').':') !!}
    {!! Form::text('cu', null, ['class' => 'form-control']) !!}
</div>

<!-- Zn Field -->
<div class="form-group col-sm-6">
    {!! Form::label('zn', __('models/samples.fields.zn').':') !!}
    {!! Form::text('zn', null, ['class' => 'form-control']) !!}
</div>

<div class="col-sm-6"></div>

<!-- na Field -->
<div class="form-group col-sm-3">
    {!! Form::label('na', __('models/samples.fields.na').':') !!}
    {!! Form::text('na', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-3">
    {!! Form::label('na_x2', __('models/samples.fields.na_x2').':') !!}
    {!! Form::text('na_x2', null, ['class' => 'form-control']) !!}
</div>

<div class="col-sm-6"></div>

<!-- calcium Field -->
<div class="form-group col-sm-3">
    {!! Form::label('calcium', __('models/samples.fields.calcium').':') !!}
    {!! Form::text('calcium', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-3">
    {!! Form::label('calcium_v1', __('models/samples.fields.calcium_v1').':') !!}
    {!! Form::text('calcium_v1', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-3">
    {!! Form::label('calcium_v2', __('models/samples.fields.calcium_v2').':') !!}
    {!! Form::text('calcium_v2', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-3">
    {!! Form::label('calcium_c', __('models/samples.fields.calcium_c').':') !!}
    {!! Form::text('calcium_c', null, ['class' => 'form-control']) !!}
</div>


<!-- magnesium Field -->
<div class="form-group col-sm-3">
    {!! Form::label('magnesium', __('models/samples.fields.magnesium').':') !!}
    {!! Form::text('magnesium', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-3">
    {!! Form::label('magnesium_v1', __('models/samples.fields.magnesium_v1').':') !!}
    {!! Form::text('magnesium_v1', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-3">
    {!! Form::label('magnesium_v2', __('models/samples.fields.magnesium_v2').':') !!}
    {!! Form::text('magnesium_v2', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-3">
    {!! Form::label('magnesium_c', __('models/samples.fields.magnesium_c').':') !!}
    {!! Form::text('magnesium_c', null, ['class' => 'form-control']) !!}
</div>


<!-- salinity Field -->
<div class="form-group col-sm-6">
    {!! Form::label('salinity', __('models/samples.fields.salinity').':') !!}
    {!! Form::text('salinity', null, ['class' => 'form-control']) !!}
</div>

<div class="col-sm-6"></div>

<!-- absorbed_sum Field -->
<div class="form-group col-sm-3">
    {!! Form::label('absorbed_sum', __('models/samples.fields.absorbed_sum').':') !!}
    {!! Form::text('absorbed_sum', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-3">
    {!! Form::label('absorbed_sum_v', __('models/samples.fields.absorbed_sum_v').':') !!}
    {!! Form::text('absorbed_sum_v', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-3">
    {!! Form::label('absorbed_sum_m', __('models/samples.fields.absorbed_sum_m').':') !!}
    {!! Form::text('absorbed_sum_m', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group col-sm-3">
    {!! Form::label('absorbed_sum_c', __('models/samples.fields.absorbed_sum_c').':') !!}
    {!! Form::text('absorbed_sum_c', null, ['class' => 'form-control']) !!}
</div>







<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(__('crud.save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('samples.index') }}" class="btn btn-light">@lang('crud.cancel')</a>
</div>
