@extends('admin.layout')
@section('titulo', "Error")

@section('content')
<div class="error-page">
    <h2 class="headline text-primary">404</h2>

    <div class="error-content">

      <h3>

        <i class="fa fa-warning text-primary"></i>

        Ooops! Página no encontrada.

      </h3>

      <p>

         Ingresa al menú lateral y allí podrás encontrar las páginas disponibles. También puedes regresar haciendo <a href="inicio">click aquí.</a>

      </p>

    </div>

  </div>
@endsection
