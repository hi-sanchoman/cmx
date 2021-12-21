<div class="table-responsive">
    <table class="table" id="samples-table">
        <thead>
            <tr>
                <th>@lang('models/samples.fields.num')</th>
                <th>@lang('models/samples.fields.point_id')</th>
                <th>@lang('models/samples.fields.date_selected')</th>
                <th>@lang('models/samples.fields.date_received')</th>
                <!-- <th>@lang('models/samples.fields.quantity')</th>
                <th>@lang('models/samples.fields.passed')</th>
                <th>@lang('models/samples.fields.accepted')</th>
                <th>@lang('models/samples.fields.notes')</th> -->
                <th colspan="3">@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($samples as $sample)

            @php
                if ($sample->point == null) continue;
                if ($sample->point->polygon == null) continue;
                if ($sample->point->polygon->field == null) continue;
                if ($sample->point->polygon->field->client == null) continue;
            @endphp

            <tr>
                <td>{{ $sample->num }}</td>
                <td>
                    Метка №{{ $sample->point->num }},
                    @if ($sample->point->polygon != null && $sample->point->polygon->field != null)
                        Поле №{{ $sample->point->polygon->field->num }},<br>

                        @if ($sample->point->polygon->field->client != null)
                            {{ $sample->point->polygon->field->client->khname }}
                        @endif
                    @endif
                </td>
                <td>{{ $sample->date_selected }}</td>
                <td>{{ $sample->date_received }}</td>
                <!-- <td>{{ $sample->quantity }}</td>
                <td>{{ $sample->passed }}</td>
                <td>{{ $sample->accepted }}</td>
                <td>{{ $sample->notes }}</td> -->
                <td class=" text-center">
                    {!! Form::open(['route' => ['samples.destroy', $sample->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <!-- <a href="{!! route('samples.show', [$sample->id]) !!}" class='btn btn-light action-btn '><i class="fa fa-eye"></i></a> -->

                        <a href="{!! route('samples.edit', [$sample->id]) !!}" target="_blank" class='btn btn-warning action-btn edit-btn'><i class="fa fa-edit"></i></a>
                        
                        {{--

                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger action-btn delete-btn', 'onclick' => 'return confirm("'.__('crud.are_you_sure').'")']) !!}

                        @if (isset($ref))
                            {!! Form::hidden('ref', $ref) !!}
                            {!! Form::hidden('field_id', $fieldId) !!}
                        @endif

                        --}}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
