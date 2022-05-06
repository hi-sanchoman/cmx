<!-- Date Started Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date_started', __('models/paths.fields.date_started').':') !!}
    {!! Form::date('date_started', null, ['class' => 'form-control','id'=>'date_started']) !!}
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
    {!! Form::label('date_completed', __('models/paths.fields.date_completed').':') !!}
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
    <a href="{{ route('paths.index') }}" class="btn btn-light">@lang('crud.cancel')</a>
</div>
