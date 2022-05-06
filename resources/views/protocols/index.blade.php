@extends('layouts.app')
@section('title')
     @lang('models/protocols.plural')
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>@lang('models/protocols.plural')</h1>
            <div class="section-header-breadcrumb">
                <!-- <a href="{{ route('protocols.create')}}" class="btn btn-primary form-btn">@lang('crud.add_new')<i class="fas fa-plus"></i></a> -->
            </div>
        </div>
    <div class="section-body">
       <div class="card">
            <div class="card-body">
                <form action="/protocols/redirect" method="POST">
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
                                <input type="submit" class="btn btn-primary" value="Сгенерировать">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
       </div>
   </div>
    
    </section>
@endsection


@section('page_js')
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endsection