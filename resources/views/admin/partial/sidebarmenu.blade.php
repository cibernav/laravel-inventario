<ul class="sidebar-menu" data-widget="tree">
<li class="header">HEADER</li>
@php
    function activeMenu($url){
        $url = strlen($url) > 1 ? substr($url, 1) : $url;
        return request()->is($url) ? 'active' : '';
    }

@endphp
<!-- Optionally, you can add icons to the links -->
<li class="{{ activeMenu('/') }}">
<a href="{{ route('dashboard') }}"><i class="fa fa-home"></i> <span>Inicio</span></a>
</li>
@if(auth::user()->hasRole(['Administrador']))
<li class="{{ activeMenu(route('admin.user.index', null, false))}}">
<a href="{{ route('admin.user.index') }}"><i class="fa fa-user"></i> <span>Usuario</span></a>
</li>
@endif
@if(auth::user()->hasRole(['Administrador', 'Especial']))
<li class="{{ activeMenu(route('admin.category.index', null, false))}}">
<a href="{{ route('admin.category.index') }}"><i class="fa fa-th"></i> <span>Categorias</span></a>
</li>
@endif
@if(auth::user()->hasRole(['Administrador', 'Especial']))
<li class="{{ activeMenu(route('admin.product.index', null, false))}}">
<a href="{{ route('admin.product.index') }}"><i class="fa fa-product-hunt"></i> <span>Productos</span></a>
</li>
@endif
@if(auth::user()->hasRole(['Administrador', 'Vendedor']))
<li class="{{ activeMenu(route('admin.client.index', null, false))}}">
<a href="{{ route('admin.client.index') }}"><i class="fa fa-users"></i> <span>Clientes</span></a>
</li>
@endif
@if(auth::user()->hasRole(['Administrador', 'Vendedor']))
<li class="treeview {{ request()->is('admin/venta*') ? 'active' : '' }}">
    <a href="#"><i class="fa fa-list-ul"></i> <span>Ventas</span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
    @if(auth::user()->hasRole(['Administrador', 'Vendedor']))
    <li class="{{ activeMenu(route('admin.venta.index', null, false))}}">
        <a href="{{ route('admin.venta.index') }}"><i class="fa fa-circle-o"></i><span>Admnistrar ventas</span></a>
    </li>
    @endif
    @if(auth::user()->hasRole(['Administrador']))
    <li class="{{ activeMenu(route('admin.venta.create', null, false))}}">
        <a href="{{ route('admin.venta.create') }}"><i class="fa fa-circle-o"></i><span>Crear venta</span></a>
    </li>
    @endif
    @if(auth::user()->hasRole(['Administrador']))
    <li class="{{ activeMenu(route('admin.reporte.ventas', null, false))}}">
        <a href="{{ route('admin.reporte.ventas') }}"><i class="fa fa-circle-o"></i><span>Reporte de ventas</span></a>
    </li>
    @endif
    </ul>
</li>
@endif
</ul>
