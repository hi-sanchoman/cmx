<?php use Carbon\Carbon; ?>

@extends('layouts.app')
@section('title')
    @lang('crud.add_new') @lang('models/protocols.singular')
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading m-0">Подготовка общего протокола</h3>
            <div class="filter-container section-header-breadcrumb row justify-content-md-end">
                <!-- <a href="{{ route('protocols.index') }}" class="btn btn-primary">@lang('crud.back')</a> -->
            </div>
        </div>
        <div class="content">
            @include('stisla-templates::common.errors')
            <div class="section-body">
               <div class="row">
                   <div class="col-lg-12">
                       <div class="card">
                           <div class="card-body ">
                                {!! Form::open(['url' => '/clients/' . $client->id . '/generate-protocol']) !!}
                                    @csrf
                                    <div class="row">
                                    	<div class="form-group col-sm-6">
										    {!! Form::label('num', 'Номер протокола:') !!}
										    {!! Form::text('num', 0, ['class' => 'form-control']) !!}
										</div>

										<div class="col-sm-6">
											{!! Form::label('protocol_date', 'Дата протокола:') !!}
										    {!! Form::date('protocol_date', Carbon::now()->format('Y-m-d'), ['class' => 'form-control']) !!}
										</div>

                                        <div class="form-group col-sm-6">
										    {!! Form::label('client_khname', 'Наименование заказчика:') !!}
										    {!! Form::text('client_khname', $client->khname, ['class' => 'form-control']) !!}
										</div>

										<div class="form-group col-sm-6">
										    {!! Form::label('object', 'Объект испытания:') !!}
										    {!! Form::text('object', 'Почва', ['class' => 'form-control']) !!}
										</div>

										<div class="form-group col-sm-6">
										    {!! Form::label('goal', 'Цель:') !!}
										    {!! Form::text('goal', 'Агрохимический анализ почвы', ['class' => 'form-control']) !!}
										</div>

										<div class="form-group col-sm-6">
										    {!! Form::label('points_num', 'Количество проб:') !!}
										    {!! Form::text('points_num', count($pointsIds), ['class' => 'form-control']) !!}
										</div>

										<div class="form-group col-sm-6">
										    {!! Form::label('date_selected', 'Дата отбора:') !!}
										    {!! Form::date('date_selected', $samples->first()->date_selected->format('Y-m-d'), ['class' => 'form-control']) !!}
										</div>

										<div class="form-group col-sm-6">
										    {!! Form::label('date_received', 'Дата поступления проб на испытание:') !!}
										    {!! Form::date('date_received', $samples->first()->date_received->format('Y-m-d'), ['class' => 'form-control']) !!}
										</div>

										<div class="form-group col-sm-6">
										    {!! Form::label('date_started', 'Дата проведения испытаний (Начало):') !!}
										    {!! Form::date('date_started', $samples->first()->date_started->format('Y-m-d'), ['class' => 'form-control']) !!}
										</div>

										<div class="form-group col-sm-6">
										    {!! Form::label('date_completed', 'Дата проведения испытаний (Конец):') !!}
										    {!! Form::date('date_completed', $samples->first()->date_completed->format('Y-m-d'), ['class' => 'form-control']) !!}
										</div>

										<div class="form-group col-sm-6">
										    {!! Form::label('field_address', 'Место отбора проб:') !!}
										    {!! Form::text('field_address', null, ['class' => 'form-control']) !!}
										</div>

										<div class="form-group col-sm-6">
										    {!! Form::label('cemex_address', 'Место проведения испытаний:') !!}
										    {!! Form::text('cemex_address', 'ИЛ ТОО "CemEX Engineering", ул. Аль-Фараби, д. 30 Б, оф. 61', ['class' => 'form-control']) !!}
										</div>

										<div class="form-group col-sm-6">
										    {!! Form::label('nd_method', 'НД на метод отбора:') !!}
										    {!! Form::text('nd_method', 'ГОСТ 28168-89', ['class' => 'form-control']) !!}
										</div>

										<div class="form-group col-sm-6">
										    {!! Form::label('act_num', 'Номер акта:') !!}
										    {!! Form::text('act_num', null, ['class' => 'form-control']) !!}
										</div>

										<div class="form-group col-sm-12">
										    {!! Form::label('nd_product', 'НД на продукцию:') !!}
										    {!! Form::textarea('nd_product', 'ГН №452 от 25.06.2015 г. Приказ МЗ РК, МООС РК, МСХ РК, МОН РК и агентства РК по управлению земельными ресурсами №99 от 30.01.2004 г; Приказ № 4-1/147 от 27 февраля 2015 года МСХ РК', ['class' => 'form-control', 'style' => 'height:100px']) !!}
										</div>

										<div class="form-group col-sm-12">
										    {!! Form::label('conditions', 'Условия проведения испытаний:') !!}
										    {!! Form::textarea('conditions', $samples->first()->date_started->format('d.m.Y') . ' - температура __°C, влажность __%. ' . $samples->first()->date_completed->format('d.m.Y') . ' - температура __°C, влажность __%.', ['class' => 'form-control', 'style' => 'height:100px']) !!}
										</div>

										<div class="form-group col-sm-6">
										    {!! Form::label('quantity', 'Количество показателей:') !!}
										    {!! Form::select('quantity', App\Models\Sample::dropdownQuantity(), null, ['class' => 'form-control']) !!}
										</div>

										<div class="form-group col-sm-12">
										    {!! Form::submit('Сгенерировать', ['class' => 'btn btn-primary']) !!}
										</div>

                                    </div>
                                {!! Form::close() !!}
                           </div>
                       </div>
                   </div>
               </div>
            </div>
        </div>
    </section>
@endsection

