@extends('layouts.app')
@section('title')
     @lang('models/fields.plural')
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>@lang('models/fields.plural')</h1>
            <div class="section-header-breadcrumb">
                <!-- <a href="/fields/editor" class="btn btn-warning form-btn" style="margin-right: 30px;">Редактор</a> -->

                <form method="get" action="/fields" class="form-inline mr-auto">
                    @csrf
                    <!-- navbar search -->
                    <div class="search-element">
                        <input class="form-control" type="search" name="query" placeholder="Поиск" aria-label="Поиск">
                        <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>

                <!-- <form action="/fields/import" method="POST" enctype="multipart/form-data" style="margin-right: 40px;">
                    @csrf

                    {!! Form::select('client_id', App\Models\Client::dropdown(), null, ['class' => 'select2', 'style' => '']) !!}

                    <input type="file" name="kml">
                    <input type="submit" name="" value="Импортировать kml">
                </form> -->

                <a href="{{ route('fields.create')}}" class="btn btn-primary form-btn">
                    @lang('crud.add_new')<i class="fas fa-plus"></i>
                </a>
                <div style="margin-right: 15px;"></div>
                <a href="{{ route('fields.import_form')}}" class="btn btn-info form-btn">
                    Импортировать
                </a>
            </div>
        </div>
    <div class="section-body">
       <div class="card">
            <div class="card-body">
                @include('fields.table')
            </div>
       </div>
   </div>
    
    </section>
@endsection



