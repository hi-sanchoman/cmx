@extends('layouts.app')
@section('title')
    Импорт поля
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Импорт поля через KML</h1>
            <div class="section-header-breadcrumb">

            </div>
        </div>
    <div class="section-body">
       <div class="card">
            <div class="card-body">
                <form action="/fields/import" method="POST" enctype="multipart/form-data" style="margin-right: 40px;">
                    @csrf

                    <div class="form-group col-md-6">
                        {!! Form::select('client_id', App\Models\Client::dropdown(), null, ['class' => 'form-control select2', 'style' => '']) !!}
                    </div>

                    <div class="form-group col-md-6">
                        <input class="form-control" type="file" name="kml">
                    </div>

                    <div class="form-group col-md-6">
                        <input type="submit" class="btn btn-primary" name="" value="Импортировать kml">
                    </div>
                </form>
            </div>
       </div>
   </div>
    
    </section>
@endsection



