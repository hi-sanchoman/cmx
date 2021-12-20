<div class="table-responsive">
    <table class="table" id="polygons-table">
        <thead>
            <tr>
                <th>@lang('models/polygons.fields.field_id')</th>
        <th>@lang('models/polygons.fields.geometry')</th>
                <th colspan="3">@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($polygons as $polygon)
            <tr>
                       <td>{{ $polygon->field_id }}</td>
            <td>{{ $polygon->geometry }}</td>
                       <td class=" text-center">
                           {!! Form::open(['route' => ['polygons.destroy', $polygon->id], 'method' => 'delete']) !!}
                           <div class='btn-group'>
                               <a href="{!! route('polygons.show', [$polygon->id]) !!}" class='btn btn-light action-btn '><i class="fa fa-eye"></i></a>
                               <a href="{!! route('polygons.edit', [$polygon->id]) !!}" class='btn btn-warning action-btn edit-btn'><i class="fa fa-edit"></i></a>
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
