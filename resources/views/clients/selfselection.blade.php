@extends('layouts.app')
@section('title')
    Подготовка к самоотбору
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading m-0">Подготовка к самоотбору</h3>
            <div class="filter-container section-header-breadcrumb row justify-content-md-end">
                <a href="/clients/{{ $client->id }}" class="btn btn-primary">@lang('crud.back')</a>
            </div>
        </div>
        <div class="content">
            @include('stisla-templates::common.errors')
            <div class="section-body">
               <div class="row">
                   <div class="col-lg-12">
                       <div class="card">
                           <div class="card-body ">

                                <form action="/clients/{{ $client->id }}/self-selection" method="POST">
                                    @csrf

                                    {{-- Field's owner --}}
                                    <div class="form-group col-sm-6">
                                        Клиент: {{ $client->khname }}
                                    </div>

                                    {{-- Field's num --}}
                                    <div class="form-group col-sm-6">
                                        {!! Form::label('qty', 'Номер поля') !!}
                                        <input class="form-control" type="number" name="field_num">
                                    </div>
                                    
                                    <!-- Region Field -->
                                    <div class="form-group col-sm-6">
                                        {!! Form::label('field_region_id', __('models/fields.fields.region_id').' поля:') !!}
                                        {!! Form::select('field_region_id', \App\Models\Region::pluck('name', 'id'), null, ['class' => 'form-control']) !!}
                                    </div>

                                    <!-- Address Field -->
                                    <div class="form-group col-sm-6">
                                        {!! Form::label('field_address', __('models/fields.fields.address').' поля:') !!}
                                        {!! Form::text('field_address', null, ['class' => 'form-control']) !!}
                                    </div>
                                                                        
                                    <!-- Square Field -->
                                    <div class="form-group col-sm-6">
                                        {!! Form::label('field_square', __('models/fields.fields.square').' поля:') !!}
                                        {!! Form::text('field_square', null, ['class' => 'form-control']) !!}
                                    </div>
                                    
                                    <!-- Type Field -->
                                    <div class="form-group col-sm-6">
                                        {!! Form::label('field_type', __('models/fields.fields.square').' поля:') !!}
                                        {!! Form::select('field_type', ['irrigated' => 'орашаемое', 'indestructible' => 'богара'], null, ['class' => 'form-control']) !!}
                                    </div>

                                    <!-- Culture Field -->
                                    <div class="form-group col-sm-6">
                                        {!! Form::label('field_culture', __('models/fields.fields.culture').' поля:') !!}
                                        {!! Form::text('field_culture', null, ['class' => 'form-control']) !!}
                                    </div>

                                    <!-- Description Field -->
                                    <div class="form-group col-sm-6 col-lg-6">
                                        {!! Form::label('field_description', __('models/fields.fields.description').' поля:') !!}
                                        {!! Form::textarea('field_description', null, ['class' => 'form-control']) !!}
                                    </div>

                                    {{-- Points' qty --}}
                                    <div class="form-group col-sm-6">
                                        {!! Form::label('qty', 'Количество меток') !!}
                                        <input class="form-control" type="number" name="qty">
                                    </div>

                                    <div class="form-group col-sm-6">
                                        <input type="submit" class="btn btn-primary" value="Добавить самоотбор">
                                    </div>
                                </form>
                                
                           </div>
                       </div>
                   </div>
               </div>
            </div>
        </div>
    </section>
@endsection

