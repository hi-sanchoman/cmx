<div class="table-responsive">
    <table class="table" id="kmls-table">
        <thead>
            <tr>
                <th>@lang('models/kmls.fields.path')</th>
        <th>@lang('models/kmls.fields.content')</th>
        <th>@lang('models/kmls.fields.field_id')</th>
                <th colspan="3">@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($kmls as $kml)
            <tr>
                       <td>{{ $kml->path }}</td>
            <td>{{ $kml->content }}</td>
            <td>{{ $kml->field_id }}</td>
                       <td class=" text-center">
                           {!! Form::open(['route' => ['kmls.destroy', $kml->id], 'method' => 'delete']) !!}
                           <div class='btn-group'>
                               <a href="{!! route('kmls.show', [$kml->id]) !!}" class='btn btn-light action-btn '><i class="fa fa-eye"></i></a>
                               <a href="{!! route('kmls.edit', [$kml->id]) !!}" class='btn btn-warning action-btn edit-btn'><i class="fa fa-edit"></i></a>
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
