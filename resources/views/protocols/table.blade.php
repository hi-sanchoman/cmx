<div class="table-responsive">
    <table class="table" id="protocols-table">
        <thead>
            <tr>
                <th>@lang('models/protocols.fields.client_id')</th>
                <!-- <th>@lang('models/protocols.fields.path')</th> -->
                <th>@lang('models/protocols.fields.access_url')</th>
                <th colspan="3">@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($protocols as $protocol)
            <tr>
                <td>
                    @if ($protocol->client != null)
                        {{ $protocol->client->khname }} ({{ $protocol->client->lastname }} {{ $protocol->client->firstname }})
                    @endif
                </td>
                <!-- <td>{{ $protocol->path }}</td> -->
                <td  style="padding-bottom: 25px">
                    @if ($protocol->access_url == null && $protocol->client != null)
                    <form action="/protocols/{{ $protocol->id }}/prepare" method="post">
                        @csrf

                        {!! Form::select('field_id', App\Models\Client::fieldsDropdown($protocol->client), null, ['class' => 'form-control']) !!}

                        {!! Form::hidden('protocol_id', $protocol->id) !!}

                        <button type="submit" class="btn btn-primary">Сгенерировать</button>
                    </form>
                        
                    @else
                        {{ $protocol->access_url }}
                    @endif
                </td>

                <td class=" text-center">
                    {!! Form::open(['route' => ['protocols.destroy', $protocol->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <!-- <a href="{!! route('protocols.show', [$protocol->id]) !!}" class='btn btn-light action-btn '><i class="fa fa-eye"></i></a>
                        <a href="{!! route('protocols.edit', [$protocol->id]) !!}" class='btn btn-warning action-btn edit-btn'><i class="fa fa-edit"></i></a> -->
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger action-btn delete-btn', 'onclick' => 'return confirm("'.__('crud.are_you_sure').'")']) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
