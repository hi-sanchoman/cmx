@extends('layouts.app')
@section('title')
     @lang('models/cartograms.plural')
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>@lang('models/cartograms.plural')</h1>
            <div class="section-header-breadcrumb">
                <!-- <a href="{{ route('cartograms.create')}}" class="btn btn-primary form-btn">@lang('crud.add_new')<i class="fas fa-plus"></i></a> -->
            </div>
        </div>
    <div class="section-body">
       <div class="card">
            <div class="card-body">
                @include('cartograms.table')
                <!-- <form action="/cartograms/redirect" method="POST">
                    @csrf
                    
                    <div class="container">
                        <div class="row">
                            <h4>Выберите клиента</h4>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                {!! Form::select('client_id', App\Models\Client::dropdown(), null, ['class' => 'select2', 'style' => '']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" value="Скачать архив">
                            </div>
                        </div>
                    </div>
                </form> -->
            </div>
       </div>
   </div>
    
    </section>
@endsection



