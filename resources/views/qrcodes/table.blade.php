<div class="table-responsive">
    <table class="table" id="qrcodes-table">
        <thead>
            <tr>
                <th>@lang('models/qrcodes.fields.point_id')</th>
                <!-- <th>@lang('models/qrcodes.fields.point_id')</th> -->
                <!-- <th>@lang('models/qrcodes.fields.content')</th> -->
                <th colspan="3">@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($qrcodes as $qrcode)

            @php
                if ($qrcode->point == null) continue;
                if ($qrcode->point->polygon == null) continue;
                if ($qrcode->point->polygon->field == null) continue;
                if ($qrcode->point->polygon->field->client == null) continue;
            @endphp

            <tr>
                <td>
                    @if ($qrcode->point != null && $qrcode->point->polygon != null && $qrcode->point->polygon->field != null)
                        {{ $qrcode->point->polygon->field->client->khname }},
                        Поле № {{ $qrcode->point->polygon->field->num }},
                        Метка №{{ $qrcode->point->num }}
                    @endif
                </td>
                
                <!-- <td>{{ '' }}</td> -->
                
                <!-- <td style="width: 300px">{{ $qrcode->content }}</td> -->
                <td class=" text-center">
                    {!! Form::open(['route' => ['qrcodes.destroy', $qrcode->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{!! route('qrcodes.show', [$qrcode->id]) !!}" target="_blank" class='btn btn-light action-btn '><i class="fa fa-eye"></i></a>
                        
                        <!-- <a href="{!! route('qrcodes.edit', [$qrcode->id]) !!}" class='btn btn-warning action-btn edit-btn'><i class="fa fa-edit"></i></a> -->
                        
                        {{-- {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger action-btn delete-btn', 'onclick' => 'return confirm("'.__('crud.are_you_sure').'")']) !!} --}}


                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
