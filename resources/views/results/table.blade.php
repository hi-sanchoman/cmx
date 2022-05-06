<div class="table-responsive">
    <table class="table" id="results-table">
        <thead>
            <tr>
                <th>@lang('models/results.fields.sample_id')</th>
<!--                 <th>@lang('models/results.fields.passed')</th>
                <th>@lang('models/results.fields.accepted')</th> -->
                <th>@lang('models/results.fields.humus')</th>
                <th>@lang('models/results.fields.ph')</th>
                <th>@lang('models/results.fields.no3')</th>
                <th>@lang('models/results.fields.p')</th>
                <th>@lang('models/results.fields.k')</th>
                <th>@lang('models/results.fields.s')</th>
                <!-- <th>@lang('models/results.fields.value7')</th>
                <th>@lang('models/results.fields.value8')</th>
                <th>@lang('models/results.fields.value9')</th>
                <th>@lang('models/results.fields.value10')</th>
                <th>@lang('models/results.fields.value11')</th>
                <th>@lang('models/results.fields.value12')</th>
                <th>@lang('models/results.fields.value13')</th> -->
                <th colspan="3">@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($results as $result)
            <tr>
                <td>
                    Проба №
                    @if ($result->sample != null)
                        {{ $result->sample->num }}
                    @endif

                    <br>
                    @if ($result->sample != null && 
                         $result->sample->point != null && 
                         $result->sample->point->polygon != null &&
                         $result->sample->point->polygon->field != null
                     )

                        <a href="/fields/{{ $result->sample->point->polygon->field->id }}">
                            Поле №{{ $result->sample->point->polygon->field->num }}
                        </a>    
                    @endif
                </td>
<!--                 <td>{{ $result->passed }}</td>
                <td>{{ $result->accepted }}</td> -->
                <td>{{ $result->humus }}</td>
                <td>{{ $result->ph }}</td>
                <td>{{ $result->no3 }}</td>
                <td>{{ $result->p }}</td>
                <td>{{ $result->k }}</td>
                <td>{{ $result->s }}</td>
                <!-- <td>{{ $result->b }}</td>
                <td>{{ $result->fe }}</td>
                <td>{{ $result->mn }}</td>
                <td>{{ $result->cu }}</td>
                <td>{{ $result->zn }}</td>
                <td>{{ $result->na }}</td>
                <td>{{ $result->calcium }}</td> -->
                <td class=" text-center">
                    {!! Form::open(['route' => ['results.destroy', $result->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <!-- <a href="{!! route('results.show', [$result->id]) !!}" class='btn btn-light action-btn '><i class="fa fa-eye"></i></a> -->
                        
                        <a href="{!! route('results.edit', [$result->id, 'mode' => '6']) !!}" target="_blank" class='btn btn-info edit-btn'>6</a>
                        
                        <a href="{!! route('results.edit', [$result->id]) !!}" target="_blank" class='btn btn-warning edit-btn'>16</a>
                        
                        {{-- 

                        {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger action-btn delete-btn', 'onclick' => 'return confirm("'.__('crud.are_you_sure').'")']) !!}

                        --}}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
