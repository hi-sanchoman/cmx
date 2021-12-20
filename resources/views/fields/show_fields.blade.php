<!-- Points -->
<h2 style="">Метки</h2 style="margin-top: 40px;">
<a href="{{ route('points.create', ['ref' => 'fields_show', 'field_id' => $field->id]) }}" class="btn btn-primary form-btn">@lang('crud.add_new')<i class="fas fa-plus"></i></a>
<div style="margin-top: 15px;"></div>
@include('points.table')


<!-- QR codes -->
<h2 style="margin-top: 40px;">QR коды</h2 style="margin-top: 40px;">
@include('qrcodes.table')

<!-- Samples -->
<h2 style="margin-top: 40px;">Пробы</h2 style="margin-top: 40px;">
@include('samples.table')

<!-- Results -->
<h2 style="margin-top: 40px;">Результаты</h2 style="margin-top: 40px;">
@include('results.table')

<!-- Cartogram -->
@if ($cartograms != null)
    <h2 style="margin-top: 40px;">Картограммы</h2 style="margin-top: 40px;">
    <form method="POST" action="/cartograms/{{ $field->id }}/prepare" style="margin-left: 0;">
        @csrf

        <div class="row" style=" padding: 0">
            <div class=" col-md-6" style="margin-bottom: 20px;">
                <label>Специалист</label>
                <input class="form-control" type="text" name="specialist" placeholder="Исполнитель">
            </div>
            <div class="col-md-6"></div>

            <div class="col-md-4" style="margin-bottom: 20px">
                <label>Дата картограммы</label>
                <input class="form-control" type="date" name="date">
            </div>
            <div class="col-md-6"></div>

            <div class="col-md-4" style="margin-bottom: 20px">
                <label>Нитратный азот</label>
                <select name="no3" class="form-control">
                    <option value="no3">Нитратный азот (0-20 см)</option>
                    <option value="no3_2">Нитратный азот (20-40 см)</option>
                </select>
            </div> 
            <div class="col-md-6"></div>

            <div class="col-md-4" style="margin-bottom: 20px">
                <label>Общая засоленность</label>
                <select name="salinity" class="form-control">
                    <option value="salinity">Глубина почвы (0-60 см)</option>
                    <option value="salinity_2">Глубина почвы (60-120 см)</option>
                </select>
            </div> 
            <div class="col-md-6"></div>

             <div class="col-md-4" style="margin-bottom: 20px">
                <label>Количество показателей</label>
                <select name="full" class="form-control">
                    <option value="part">6 показетелей</option>
                    <option value="full">16 показателей</option>
                </select>
            </div> 
            <div class="col-md-6"></div>

            <div class="col-md-3">
                <input type="submit" class="btn btn-primary" value="Сгенерировать">
            </div>
        </div>

    </form>

    @if ($cartograms[0]->status == 'completed')
        <div class="col-md-3" style="margin-top: 30px; padding: 0;">
            <p>Картограмма готова...</p>
            <a class="btn btn-success" href="{{ asset($cartograms[0]->access_url) }}">Скачать архив</a>
        </div>
    @endif        

@endif

<!-- Protocol -->
@if ($protocol != null)
<h2 style="margin-top: 40px;">Протокол</h2 style="margin-top: 40px;">
<form action="/protocols/{{ $protocol->id }}/prepare" method="post">
    @csrf

    {!! Form::hidden('field_id', $field->id) !!}
    {!! Form::hidden('protocol_id', $protocol->id) !!}

    <button type="submit" class="btn btn-primary">Сгенерировать</button>
</form>
@endif