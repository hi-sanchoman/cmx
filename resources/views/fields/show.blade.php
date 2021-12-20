@extends('layouts.app')
@section('title')
    @lang('models/fields.singular')  @lang('crud.details') 
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
        <h1>@lang('models/fields.singular') @lang('crud.details')</h1>
        <div class="section-header-breadcrumb">
            @if (isset($ref) && $ref != '')
                <a href="/clients/{{ $field->client_id }}"  class="btn btn-primary">@lang('crud.back')</a>
            @else
                <a href="{{ route('fields.index') }}"  class="btn btn-primary">@lang('crud.back')</a>
            @endif
        </div>
      </div>
   @include('stisla-templates::common.errors')
    <div class="section-body">
        <div class="card">
            <div class="card-body">
                @include('fields.show_fields')
            </div>
        </div>
    </div>
    </section>
@endsection

