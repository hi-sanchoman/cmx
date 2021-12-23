<li class="side-menus {{ Request::is('*') ? 'active' : '' }}">
    <a class="nav-link" href="/">
        <i class=" fas fa-building"></i><span>Приветствие</span>
    </a>
</li>

<li class="menu-header">CRM</li>

<li class="{{ Request::is('clients*') ? 'active' : '' }}">
    <a href="{{ route('clients.index') }}"><i class="fa fa-users"></i><span>@lang('models/clients.plural')</span></a>
</li>


<li class="menu-header">Данные</li>

<li class="{{ Request::is('fields*') ? 'active' : '' }}">
    <a href="{{ route('fields.index') }}"><i class="fa fa-globe"></i><span>@lang('models/fields.plural')</span></a>
</li>

<!-- <li class="{{ Request::is('trips*') ? 'active' : '' }}">
    <a href="{{ route('trips.index') }}"><i class="fa fa-edit"></i><span>@lang('models/trips.plural')</span></a>
</li>

<li class="{{ Request::is('kmls*') ? 'active' : '' }}">
    <a href="{{ route('kmls.index') }}"><i class="fa fa-edit"></i><span>@lang('models/kmls.plural')</span></a>
</li>

<li class="{{ Request::is('polygons*') ? 'active' : '' }}">
    <a href="{{ route('polygons.index') }}"><i class="fa fa-edit"></i><span>@lang('models/polygons.plural')</span></a>
</li>

<li class="{{ Request::is('subpolygons*') ? 'active' : '' }}">
    <a href="{{ route('subpolygons.index') }}"><i class="fa fa-edit"></i><span>@lang('models/subpolygons.plural')</span></a>
</li>

<li class="{{ Request::is('points*') ? 'active' : '' }}">
    <a href="{{ route('points.index') }}"><i class="fa fa-edit"></i><span>@lang('models/points.plural')</span></a>
</li> -->

<!-- <li class="{{ Request::is('qrcodes*') ? 'active' : '' }}">
    <a href="{{ route('qrcodes.index') }}"><i class="fa fa-edit"></i><span>@lang('models/qrcodes.plural')</span></a>
</li> -->

<li class="menu-header">Выезды</li>

<li class="{{ Request::is('paths*') ? 'active' : '' }}">
    <a href="{{ route('paths.index') }}"><i class="fa fa-route"></i><span>@lang('models/paths.plural')</span></a>
</li>

<li class="menu-header">Лаборатория</li>

<li class="{{ Request::is('samples*') ? 'active' : '' }}">
    <a href="{{ route('samples.index') }}"><i class="fa fa-flask"></i><span>@lang('models/samples.plural')</span></a>
</li>

<li class="{{ Request::is('results*') ? 'active' : '' }}">
    <a href="{{ route('results.index') }}"><i class="fa fa-clipboard"></i><span>@lang('models/results.plural')</span></a>
</li>

<li class="menu-header">Результаты</li>

<li class="{{ Request::is('cartograms*') ? 'active' : '' }}">
    <a href="{{ route('cartograms.index') }}"><i class="fa fa-map"></i><span>@lang('models/cartograms.plural')</span></a>
</li>

<li class="{{ Request::is('protocols*') ? 'active' : '' }}">
    <a href="{{ route('protocols.index') }}"><i class="fa fa-file-alt"></i><span>@lang('models/protocols.plural')</span></a>
</li>


<li class="menu-header">Справочники</li>


<li class="{{ Request::is('regions*') ? 'active' : '' }}">
    <a href="{{ route('regions.index') }}"><i class="fa fa-city"></i><span>@lang('models/regions.plural')</span></a>
</li>


