@extends('layouts.app')
@section('title')
    Подготовка к импорту
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading m-0">Подготовка к импорту</h3>
            <div class="filter-container section-header-breadcrumb row justify-content-md-end">
                <!-- <a href="{{ back() }}" class="btn btn-primary">@lang('crud.back')</a> -->
            </div>
        </div>
        <div class="content">
            @include('stisla-templates::common.errors')
            <div class="section-body">
               <div class="row">
                   <div class="col-lg-12">
                       <div class="card">
                           <div class="card-body ">

                           		
                                @if (count($fields) > 0)
                                    <table class="table">
                               			<tr>
                               				<th>Кад. номер</th>
                               				<th>Площадь</th>
                               				<th>Метки</th>
                               			</tr>
                               			@foreach ($fields as $field)
                               			<tr>
                               				<td>{{ $field['cadnum'] }}</td>
                               				<td>{{ $field['square'] }}</td>
                               				<td>
                               					({{ count($field['points']) }} шт.)
                               					@foreach ($field['points'] as $point)
                               						{{ $point['name'] }}, 
                               					@endforeach
                               				</td>

                               			</tr>
    		                           	@endforeach 
                               		</table>

                               		<form method="POST" action="/fields/generate-import">
                               			@csrf

                               			{!! Form::hidden('client_id', $client->id) !!}
                               			{!! Form::hidden('fields', json_encode($fields)) !!}

                               			<input type="submit" class="btn btn-primary" value="Импортировать">
                               		</form>
                                @else
                                    <p>Ошибка: неверный формат KML</p>

                                    <a class="btn btn-primary" href="/import_form">Вернуться назад</a>
                                @endif
                           </div>
                       </div>
                   </div>
               </div>
            </div>
        </div>
    </section>
@endsection

