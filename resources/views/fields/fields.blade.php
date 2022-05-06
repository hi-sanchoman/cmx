<!-- Client Field -->
<div class="form-group col-sm-6">
    {!! Form::label('client_id', __('models/fields.fields.client_id').':') !!}
    {!! Form::select('client_id', \App\Models\Client::dropdown(), null, ['class' => 'form-control']) !!}
</div>

<!-- Num Field -->
<div class="form-group col-sm-6">
    {!! Form::label('num', __('models/fields.fields.num').':') !!}
    @if(isset($latestNum))
        {!! Form::text('num', $latestNum + 1, ['class' => 'form-control']) !!}
    @else
        {!! Form::text('num', null, ['class' => 'form-control']) !!}
    @endif
</div>

<!-- Cadnum Field -->
<div class="form-group col-sm-6">
    {!! Form::label('cadnum', __('models/fields.fields.cadnum').':') !!}
    {!! Form::text('cadnum', null, ['class' => 'form-control']) !!}
</div>

<!-- Address Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address', __('models/fields.fields.address').':') !!}
    {!! Form::text('address', null, ['class' => 'form-control']) !!}
</div>

<!-- Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type', __('models/fields.fields.square').':') !!}
    {!! Form::select('type', ['irrigated' => 'орашаемое', 'indestructible' => 'богара'], null, ['class' => 'form-control']) !!}
</div>

<!-- Square Field -->
<div class="form-group col-sm-6">
    {!! Form::label('square', __('models/fields.fields.square').':') !!}
    {!! Form::text('square', null, ['class' => 'form-control']) !!}
</div>

<!-- Culture Field -->
<div class="form-group col-sm-6">
    {!! Form::label('culture', __('models/fields.fields.culture').':') !!}
    {!! Form::text('culture', null, ['class' => 'form-control']) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('description', __('models/fields.fields.description').':') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
</div>

<!-- Region Field -->
<div class="form-group col-sm-6">
    {!! Form::label('region_id', __('models/fields.fields.region_id').':') !!}
    {!! Form::select('region_id', \App\Models\Region::pluck('name', 'id'), null, ['class' => 'form-control']) !!}
</div>

@if (isset($ref) && $ref == 'fields_show')
    {!! Form::hidden('ref', $ref) !!}
    {!! Form::hidden('field_id', $fieldId) !!}
@endif


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(__('crud.save'), ['class' => 'btn btn-primary']) !!}
    <!-- <a href="{{ route('fields.index') }}" class="btn btn-light">@lang('crud.cancel')</a> -->
</div>
