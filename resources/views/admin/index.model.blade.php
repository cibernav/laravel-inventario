@extends('admin.layout')

@section('title', 'Administrar usuarios')
@section('description', '')
@push('styles')
@endpush
@push('scripts')
<script>
    $(".tabla").DataTable({
        "language": {
            "url" : "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
        }
    });
</script>
@endpush
@section('content')
<!-- Default box -->
<div class="box">
    <div class="box-header with-border">
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddUser">Agregar usuario</button>
    </div>
    <div class="box-body">
        <table class="table table-border table-striped tabla dt-responsive">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Nombre</th>
                    <th>email</th>
                    <th>Foto</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Ultimo Login</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $item)
                <tr>
                    <td>{{$item->id}}</td>
                    <td>{{$item->name}}</td>
                    <td>{{$item->email}}</td>
                    <td>
                    <img src="{{ url('/storage/img/anonymous.png')}}" class="img-thumbnail" width="40px" >
                    </td>
                    <td></td>
                    <td>
                        <button class="btn {{ $item->estado ? 'btn-success' : '' }}  btn-xs">Activo</button>
                    </td>
                    <td>{{$item->updated_at->Format('d-m-y')}}</td>
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></button>
                            <button class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->


<!-- Modal -->
<div class="modal fade" id="modalAddUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <form action="{{ route('admin.user.store')}}" method="POST" enctype="multipart/form-data">
            {!! csrf_field() !!}
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Agregar Usuario</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control input-lg" name="name" placeholder="Ingresar nombre" required>
                            </div>
                        </div>
                        <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                    <input type="email" class="form-control input-lg" name="email" placeholder="Ingresar email" required>
                                </div>
                        </div>
                        <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                    <input type="password" class="form-control input-lg" name="password" placeholder="Ingresar contraseña" required>
                                </div>
                        </div>
                        <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-users"></i></span>
                                    <select class="form-control input-lg" name = "role">
                                        <option value="">Selecciona Rol</option>
                                        <option value="1">Administrador</option>
                                    </select>
                                </div>
                        </div>
                        <div class="form-group">
                            <div class="panel">
                                SUBIR FOTO
                            </div>
                            <input type="file" name="photo">
                            <p class="help-block">Peso maximo de la foto 20 MB</p>
                            <img src="{{ url('/storage/img/anonymous.png')}}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
