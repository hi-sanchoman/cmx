<!-- Num Field -->
<div class="form-group col-sm-6">
    {!! Form::label('num', __('models/clients.fields.num').':') !!}
    @if(isset($latestNum))
        {!! Form::text('num', $latestNum + 1, ['class' => 'form-control']) !!}
    @else
        {!! Form::text('num', null, ['class' => 'form-control']) !!}
    @endif
</div>

<!-- Iin Field -->
<div class="form-group col-sm-6">
    {!! Form::label('iin', __('models/clients.fields.iin').':') !!}
    {!! Form::text('iin', null, ['class' => 'form-control']) !!}
</div>

<!-- Firstname Field -->
<div class="form-group col-sm-6">
    {!! Form::label('firstname', __('models/clients.fields.firstname').':') !!}
    {!! Form::text('firstname', null, ['class' => 'form-control']) !!}
</div>

<!-- Lastname Field -->
<div class="form-group col-sm-6">
    {!! Form::label('lastname', __('models/clients.fields.lastname').':') !!}
    {!! Form::text('lastname', null, ['class' => 'form-control']) !!}
</div>

<!-- KhName Field -->
<div class="form-group col-sm-6">
    {!! Form::label('khname', __('models/clients.fields.khname').':') !!}
    {!! Form::text('khname', null, ['class' => 'form-control']) !!}
</div>

<!-- Region Field -->
<div class="form-group col-sm-6">
    {!! Form::label('region_id', __('models/clients.fields.region_id').':') !!}
    {!! Form::select('region_id', \App\Models\Region::pluck('name', 'id'), null, ['class' => 'form-control']) !!}
</div>

<!-- Address Field -->
<div class="form-group col-sm-6">
    {!! Form::label('address', __('models/clients.fields.address').':') !!}
    {!! Form::text('address', null, ['class' => 'form-control']) !!}
</div>

<!-- Phone Field -->
<div class="form-group col-sm-6">
    {!! Form::label('phone', __('models/clients.fields.phone').':') !!}
    {!! Form::text('phone', null, ['class' => 'form-control']) !!}
</div>

<!-- Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('email', __('models/clients.fields.email').':') !!}
    {!! Form::text('email', null, ['class' => 'form-control']) !!}
</div>

<!-- Password Field -->
<div class="form-group col-sm-6">
    {!! Form::label('password', __('models/clients.fields.password').':') !!}
    {!! Form::text('password', null, ['class' => 'form-control']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(__('crud.save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('clients.index') }}" class="btn btn-light">@lang('crud.cancel')</a>
</div>
