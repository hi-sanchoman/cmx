<div class="table-responsive">
    <table class="table" id="paths-table">
        <thead>
            <tr>
                <th>@lang('models/paths.fields.unit')</th>
                <th>@lang('models/paths.fields.date_started')</th>
                <th>@lang('models/paths.fields.date_completed')</th>
                <th colspan="3">@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($paths as $path)
            <tr>
                <td>{{ $path->unit }}</td>
                <td>{{ $path->date_started->format('d.m.Y') }}</td>
                <td>{{ $path->date_completed->format('d.m.Y') }}</td>

                <td class=" text-center">
                    {!! Form::open(['route' => ['paths.destroy', $path->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('paths.show', [$path->id]) !!}" class='btn btn-light' target="_blank">Скачать в KML</a>
                        


                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger action-btn delete-btn', 'onclick' => 'return confirm("'.__('crud.are_you_sure').'")']) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
