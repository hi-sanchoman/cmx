@extends('layouts.app')
@section('title')
     @lang('models/samples.plural')
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>@lang('models/samples.plural')</h1>
            <div class="section-header-breadcrumb">
                <form method="get" action="/samples" class="form-inline mr-auto">
                    @csrf
                    <!-- navbar search -->
                    <div class="search-element">
                        <input class="form-control" type="search" name="query" placeholder="Поиск" aria-label="Поиск">
                        <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>

                <!-- <a href="{{ route('samples.create')}}" class="btn btn-primary form-btn">@lang('crud.add_new')<i class="fas fa-plus"></i></a> -->
            </div>
        </div>
    <div class="section-body">
       <div class="card">
            <div class="card-body">
                @include('samples.table')
            </div>
       </div>
   </div>
    
    </section>
@endsection



