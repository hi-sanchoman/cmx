@extends('layouts.app')
@section('title')
    @lang('crud.edit') @lang('models/fields.singular')
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading m-0">@lang('crud.edit') @lang('models/fields.singular')</h3>
            <div class="filter-container section-header-breadcrumb row justify-content-md-end">
                @if (isset($ref) && $ref != '')
                    <a href="/clients/{{ $field->client_id }}"  class="btn btn-primary">@lang('crud.back')</a>
                @else
                    <a href="{{ route('fields.index') }}"  class="btn btn-primary">@lang('crud.back')</a>
                @endif
            </div>
        </div>
        <div class="content">
              @include('stisla-templates::common.errors')
              <div class="section-body">
                 <div class="row">
                     <div class="col-lg-12">
                         <div class="card">
                             <div class="card-body ">
                                {!! Form::model($field, ['route' => ['fields.update', $field->id], 'method' => 'patch']) !!}
                                    <div class="row">
                                        @include('fields.fields')
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
