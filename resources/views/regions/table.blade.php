<div class="table-responsive">
    <table class="table" id="regions-table">
        <thead>
            <tr>
                <th>@lang('models/regions.fields.name')</th>
                <th colspan="3">@lang('crud.action')</th>
            </tr>
        </thead>
        <tbody>
        @foreach($regions as $region)
            <tr>
                       <td>{{ $region->name }}</td>
                       <td class=" text-center">
                           {!! Form::open(['route' => ['regions.destroy', $region->id], 'method' => 'delete']) !!}
                           <div class='btn-group'>
                               <a href="{!! route('regions.show', [$region->id]) !!}" class='btn btn-light action-btn '><i class="fa fa-eye"></i></a>
                               <a href="{!! route('regions.edit', [$region->id]) !!}" class='btn btn-warning action-btn edit-btn'><i class="fa fa-edit"></i></a>
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
