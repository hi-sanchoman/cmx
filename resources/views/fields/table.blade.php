<div class="table-responsive">
    <table class="table" id="fields-table">
        <thead>
            <tr>
                <th>@lang('models/fields.fields.client_id')</th>
                <th>@lang('models/fields.fields.cadnum')</th>
                <th>@lang('models/fields.fields.num')</th>
                <th>@lang('models/fields.fields.type')</th>
                <th>@lang('models/fields.fields.square')</th>
                <!-- <th>@lang('models/fields.fields.culture')</th> -->
                <!-- <th>@lang('models/fields.fields.description')</th> -->
                <th>@lang('models/fields.fields.region_id')</th>
                <th colspan="3">@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($fields as $field)

            @php 
                if ($field->client == null) continue;
            @endphp

            <tr>
                <td>{{ $field->client->khname }}</td>
                <td>{{ $field->cadnum }}</td>
                <td>Поле №{{ $field->num }}</td>
                <td>@lang('common.' . $field->type)</td>
                <td>{{ $field->square }}</td>
                <!-- <td>{{ $field->culture }}</td>
                <td>{{ $field->description }}</td> -->
                <td>{{ $field->region->name }}</td>
                <td class=" text-center">
                    {!! Form::open(['route' => ['fields.destroy', $field->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        @if (isset($ref) && $ref == 'clients_show')
                            <a href="{!! route('fields.show', [$field->id, 'ref' => $ref, 'client_id' => $client->id]) !!}" class='btn btn-light action-btn '><i class="fa fa-eye"></i></a>

                            <a href="{!! route('fields.edit', [$field->id, 'ref' => $ref, 'client_id' => $client->id]) !!}" class='btn btn-warning action-btn edit-btn'><i class="fa fa-edit"></i></a>
                        @else
                            <a href="{!! route('fields.show', [$field->id]) !!}" class='btn btn-light action-btn '><i class="fa fa-eye"></i></a>

                            <a href="{!! route('fields.edit', [$field->id]) !!}" class='btn btn-warning action-btn edit-btn'><i class="fa fa-edit"></i></a>
                        @endif

                        @if ($field->is_selfselection == 0)
                            <a href="{!! route('fields.map', [$field->id]) !!}" target="_blank" class='btn btn-light action-btn '><i class="fa fa-map"></i></a>
                        @endif

                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger action-btn delete-btn', 'onclick' => 'return confirm("'.__('crud.are_you_sure').'")']) !!}

                        @if (isset($ref) && $ref == 'clients_show')
                            {!! Form::hidden('ref', $ref) !!}
                            {!! Form::hidden('client_id', $client->id) !!}
                        @endif
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
