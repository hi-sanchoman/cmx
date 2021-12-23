<div class="table-responsive">
    <table class="table" id="clients-table">
        <thead>
            <tr>
                <th>@lang('models/clients.fields.num')</th>
                <th>@lang('models/clients.fields.firstname')</th>
                <th>@lang('models/clients.fields.lastname')</th>
                <th>@lang('models/clients.fields.khname')</th>
                <th>@lang('models/clients.fields.region_id')</th>
                <th>@lang('models/clients.fields.phone')</th>
                <!-- <th>@lang('models/clients.fields.address')</th>
                <th>@lang('models/clients.fields.email')</th> -->
                <th colspan="3">@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($clients as $client)
            <tr>
                <td>{{ $client->num }}</td>
                <td>{{ $client->firstname }}</td>
                <td>{{ $client->lastname }}</td>
                <td>{{ $client->khname }}</td>
                <td>{{ $client->region->name }}</td>
                <!-- <td>{{ $client->address }}</td>
                <td>{{ $client->email }}</td> -->
                <td>{{ $client->phone }}</td>
                <td class=" text-center">
                    {!! Form::open(['route' => ['clients.destroy', $client->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('clients.show', [$client->id]) !!}" class='btn btn-light action-btn '><i class="fa fa-eye"></i></a>
                        <a href="{!! route('clients.edit', [$client->id]) !!}" class='btn btn-warning action-btn edit-btn'><i class="fa fa-edit"></i></a>

                        <a href="/clients/{{ $client->id }}/cabinet?token={{ md5($client->password) }}" class='btn btn-info action-btn edit-btn' style="margin-right: 20px;" target="_blank"><i class="fa fa-users"></i></a>
                        

                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger action-btn delete-btn', 'onclick' => 'return confirm("'.__('crud.are_you_sure').'")']) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
