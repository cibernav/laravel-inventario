@extends('admin.layout')

@section('title', 'Tablero')
@section('description', 'Panel de Control')
@section('content')

<div class="row">
    <div>
        @if (auth::user()->hasRole(['Administrador']))
        @include('admin.home._cajas')
        @endif
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        @if (auth::user()->hasRole(['Administrador']))
        @include('admin.reporte.venta.graficoventa')
        @endif
    </div>

    <div class="col-lg-6">
        @if (auth::user()->hasRole(['Administrador']))
        @include('admin.reporte.venta.producto')
        @endif
    </div>
    <div class="col-lg-6">
        @if (auth::user()->hasRole(['Administrador']))
        @include('admin.home._productoreciente')
        @endif
    </div>
    <div class="col-lg-12">
        @if (auth::user()->hasRole(['Vendedor', 'Especial']))
        <div class="box box-success">
            <h1>Bienvenid@ {{ auth::user()->name }}</h1>
        </div>

        @endif
    </div>
</div>
@endsection
