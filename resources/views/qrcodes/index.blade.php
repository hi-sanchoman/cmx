@extends('layouts.app')
@section('title')
     @lang('models/qrcodes.plural')
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>@lang('models/qrcodes.plural')</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('qrcodes.create')}}" class="btn btn-primary form-btn">@lang('crud.add_new')<i class="fas fa-plus"></i></a>
            </div>
        </div>
    <div class="section-body">
       <div class="card">
            <div class="card-body">
                @include('qrcodes.table')
            </div>
       </div>
   </div>
    
    </section>
@endsection



