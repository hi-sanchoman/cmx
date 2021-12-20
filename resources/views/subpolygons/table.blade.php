<div class="table-responsive">
    <table class="table" id="subpolygons-table">
        <thead>
            <tr>
                <th>@lang('models/subpolygons.fields.polygon_id')</th>
        <th>@lang('models/subpolygons.fields.geometry')</th>
                <th colspan="3">@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($subpolygons as $subpolygon)
            <tr>
                       <td>{{ $subpolygon->polygon_id }}</td>
            <td>{{ $subpolygon->geometry }}</td>
                       <td class=" text-center">
                           {!! Form::open(['route' => ['subpolygons.destroy', $subpolygon->id], 'method' => 'delete']) !!}
                           <div class='btn-group'>
                               <a href="{!! route('subpolygons.show', [$subpolygon->id]) !!}" class='btn btn-light action-btn '><i class="fa fa-eye"></i></a>
                               <a href="{!! route('subpolygons.edit', [$subpolygon->id]) !!}" class='btn btn-warning action-btn edit-btn'><i class="fa fa-edit"></i></a>
                               {!! Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger action-btn delete-btn', 'onclick' => 'return confirm("'.__('crud.are_you_sure').'")']) !!}
                           </div>
                           {!! Form::close() !!}
                       </td>
                   </tr>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
