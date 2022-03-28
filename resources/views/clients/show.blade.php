@extends('layouts.app')
@section('title')
    @lang('models/clients.singular')  @lang('crud.details') 
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>@lang('models/clients.singular') @lang('crud.details')</h1>
            <div class="section-header-breadcrumb">
                <a href="/clients/{{ $client->id }}/self-selection" class="btn btn-success" style="margin-right: 10px;">+ Самоотбор</a>

                <a href="/clients/{{ $client->id }}/cartograms" class="btn btn-warning" style="margin-right: 10px;">Скачать картограммы</a>
                
                <a href="/clients/{{ $client->id }}/protocol" class="btn btn-info" style="margin-right: 10px;">Скачать общий протокол</a>
                <a href="{{ route('clients.index') }}" class="btn btn-primary form-btn float-right">@lang('crud.back')</a>
            </div>
        </div>
        @include('stisla-templates::common.errors')
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @include('clients.show_fields')
                </div>
            </div>
        </div>
    </section>
@endsection