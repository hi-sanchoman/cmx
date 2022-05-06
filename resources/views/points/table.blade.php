<div class="table-responsive">
    <table class="table" id="points-table">
        <thead>
            <tr>
                <th>@lang('models/points.fields.polygon_id')</th>
                <th>@lang('models/points.fields.lat')</th>
                <th>@lang('models/points.fields.lon')</th>
                <th colspan="3">@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($points as $point)
            <tr>
                <td>Метка №{{ $point->num }}</td>
                <td>{{ $point->lat }}</td>
                <td>{{ $point->lon }}</td>
                <td class=" text-center">
                    {!! Form::open(['route' => ['points.destroy', $point->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <!-- <a href="{!! route('points.show', [$point->id]) !!}" class='btn btn-light action-btn '><i class="fa fa-eye"></i></a> -->
                        
                        @if (isset($ref))
                            <a href="{!! route('points.edit', [$point->id, 'ref' => 'fields_show', 'field_id' => $fieldId]) !!}" class='btn btn-warning action-btn edit-btn'><i class="fa fa-edit"></i></a>
                        @else
                            <a href="{!! route('points.edit', [$point->id]) !!}" class='btn btn-warning action-btn edit-btn'><i class="fa fa-edit"></i></a>
                        @endif

                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger action-btn delete-btn', 'onclick' => 'return confirm("'.__('crud.are_you_sure').'")']) !!}

                        @if (isset($ref))
                            {!! Form::hidden('ref', $ref) !!}
                            {!! Form::hidden('field_id', $fieldId) !!}
                        @endif
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
