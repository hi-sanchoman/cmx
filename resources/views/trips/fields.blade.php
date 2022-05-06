<!-- Field Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('field_id', __('models/trips.fields.field_id').':') !!}
    {!! Form::select('field_id', \App\Models\Field::pluck('cadnum', 'id'), null, ['class' => 'form-control']) !!}
</div>

<!-- Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date', __('models/trips.fields.date').':') !!}
    {!! Form::date('date', null, ['class' => 'form-control','id'=>'date']) !!}
</div>

@push('scripts')
    <script type="text/javascript">
        $('#date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false
        })
    </script>
@endpush

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', __('models/trips.fields.status').':') !!}
    {!! Form::select('status', ['pending' => 'в ожидании', 'started' => 'начато', 'completed' => 'завершено'], null, ['class' => 'form-control']) !!}
</div>

<!-- Date Completed Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date_completed', __('models/trips.fields.date_completed').':') !!}
    {!! Form::date('date_completed', null, ['class' => 'form-control','id'=>'date_completed']) !!}
</div>

@push('scripts')
    <script type="text/javascript">
        $('#date_completed').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: false
        })
    </script>
@endpush

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(__('crud.save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('trips.index') }}" class="btn btn-light">@lang('crud.cancel')</a>
</div>
