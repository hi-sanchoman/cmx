<div class="table-responsive">
    <table class="table" id="cartograms-table">
        <thead>
            <tr>
                <th>@lang('models/cartograms.fields.field_id')</th>
                <!-- <th>@lang('models/cartograms.fields.status')</th> -->
                <th>@lang('models/cartograms.fields.access_url')</th>
                <!-- <th colspan="3">@lang('crud.action')</th> -->
            </tr>
        </thead>
        <tbody>
        @foreach($cartograms as $cartogram)
            <tr>
                <td>
                    @if ($cartogram->field != null)
                        {{ $cartogram->field->client->khname }}, Поле №{{ $cartogram->field->num }}
                    @endif
                </td>
                <!-- <td>{{ $cartogram->status }}</td> -->
                <td>
                    <a target="_blank" href="{{ asset($cartogram->access_url) }}">Скачать архив</a>
                </td>

                {{--<td class=" text-center">
                        <div class='btn-group'>
                            <a class="btn btn-light action-btn" href="/show-cartogram/{{ $cartogram->id }}/humus"><i class="fa fa-eye"></i></a>
                        {!! Form::open(['route' => ['cartograms.destroy', $cartogram->id], 'method' => 'delete']) !!}

                            <!-- <a href="{!! route('cartograms.show', [$cartogram->id]) !!}" class='btn btn-light action-btn '><i class="fa fa-eye"></i></a> -->
                            <!-- <a href="{!! route('cartograms.edit', [$cartogram->id]) !!}" class='btn btn-warning action-btn edit-btn'><i class="fa fa-edit"></i></a> -->
                            {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger action-btn delete-btn', 'onclick' => 'return confirm("'.__('crud.are_you_sure').'")']) !!}
                        </div>
                        {!! Form::close() !!}
                </td>--}}
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
