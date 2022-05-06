<div class="table-responsive">
    <table class="table" id="trips-table">
        <thead>
            <tr>
                <th>@lang('models/trips.fields.client')</th>
                <th>@lang('models/trips.fields.field_id')</th>
                <th>@lang('models/trips.fields.region')</th>
                <th>@lang('models/trips.fields.date')</th>
                <th>@lang('models/trips.fields.status')</th>
                <th>@lang('models/trips.fields.date_completed')</th>
                <th colspan="3">@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($trips as $trip)
            <tr>
                <td>
                    @if ($trip->field != null && $trip->field->client != null)
                        {{ $trip->field->client->lastname }} 
                        {{ $trip->field->client->firstname }}
                    @endif
                </td>
                <td>
                    @if ($trip->field != null)
                        Поле №{{ $trip->field->num }} ({{ $trip->field->cadnum }})
                    @endif 
                </td>
                <td>
                    @if ($trip->field != null)
                        {{ $trip->field->region->name }}
                    @endif
                </td>
                <td>{{ $trip->date }}</td>
                <td>{{ $trip->status }}</td>
                <td>{{ $trip->date_completed }}</td>
                <td class=" text-center">
                    <!-- {!! Form::open(['route' => ['trips.destroy', $trip->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('trips.show', [$trip->id]) !!}" class='btn btn-light action-btn '><i class="fa fa-eye"></i></a>
                        <a href="{!! route('trips.edit', [$trip->id]) !!}" class='btn btn-warning action-btn edit-btn'><i class="fa fa-edit"></i></a>
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger action-btn delete-btn', 'onclick' => 'return confirm("'.__('crud.are_you_sure').'")']) !!}
                    </div>
                    {!! Form::close() !!} -->
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
