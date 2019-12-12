@extends('admin.layout')

@section('title', 'Administrar usuarios')
@section('description', '')
@push('styles')
<style>
input[type=file].form-control {
    height: auto;
}
</style>
@endpush

@section('content')

<!-- Default box -->
<div class="box">
    <div class="box-header with-border">
        <button id="btnCreate" class="btn btn-primary">Agregar usuario</button>
    </div>
    <div class="box-body">
        <table class="table table-border table-striped tabla dt-responsive" width="100%">
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
                    <img src="{{ url($item->Photo)}}" class="img-thumbnail" width="40px" >
                    </td>
                    <td>{{ $item->getRoleNames()->implode(', ') }}</td>
                    <td>
                        <button data-id='{{$item->id}}' data-active={{ $item->estado ? '1' : '0' }}  class="btn {{ $item->estado ? 'btn-success' : 'btn-danger' }}  btn-xs btnActive">{{$item->estado ? 'Activo' : 'No Activo' }}</button>
                    </td>
                    <td>{{$item->DateLogin}}</td>
                    <td>
                    <form action="{{ route('admin.user.destroy', $item->id) }}" method="POST">
                            {!! csrf_field() !!}
                            {{ method_field('DELETE') }}
                            <div class="btn-group">
                                    <button type="button" class="btn btn-warning btn-xs" onclick="return editUser({{ $item->id }});" ><i class="fa fa-pencil"></i></button>

                            <button type="button" class="btn btn-danger btn-xs" data-user="{{ $item->email }}" onclick="return fndeleteUser(this, {{ $item->id }});"><i class="fa fa-times"></i></button>
                            </div>
                        </form>

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
        <form action="{{ route('admin.user.store')}}" method="POST" enctype="multipart/form-data" id="userform">
                <input type="hidden" value="{{ csrf_token() }}" name="_token" id="_token">
                <input type="hidden" value="create" id="user_oper">
                <input type="hidden" value="" id="user_id">
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Agregar Usuario</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <div class="input-group {{ $errors->has('name') ? 'has-error' : '' }} ">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control input-lg" name="name" id="user_name" value="{{ old('name') }}" placeholder="Ingresar nombre" autofocus>
                                <span id="sp_name" class="label label-danger"></span>
                            </div>
                        </div>
                        <div class="form-group">
                                <div class="input-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                    <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                    <input type="email" class="form-control input-lg" name="email" id="user_email" value="{{ old('email') }}" placeholder="Ingresar email">
                                    <span id="sp_email" class="label label-danger"></span>
                                </div>
                        </div>
                        <div class="form-group">
                                <div class="input-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                    <input type="password" class="form-control input-lg" name="password" id="user_password" placeholder="Ingresar contraseña">
                                    <span id="sp_password" class="label label-danger"></span>
                                </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group {{ $errors->has('role') ? 'has-error' : '' }}">
                                <span class="input-group-addon"><i class="fa fa-users"></i></span>
                                <select class="form-control input-lg" name = "role" id="user_role">
                                    <option value="">Selecciona Perfil</option>
                                    @foreach ($roles as $item)
                                        <option {{ old('role') ==$item->name ? 'selected' : ''}} value="{{ $item->name }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <span id="sp_role" class="label label-danger"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="panel">
                                SUBIR FOTO
                            </div>
                            <input type="file" name="photo" id="photo" class="form-control">
                            <p class="help-block">Peso maximo de la foto 20 MB</p>
                            <img class="img-bordered-sm" id="user_image" src="{{ url('/storage/anonymous_user.png')}}" width="140px">
                            <span id="sp_photo" class="label label-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit"  class="btn btn-primary" onclick="return saveUser(event);" >Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


@push('scripts')
@if($errors->any())
<script>
    //alert('Hubo un error');
</script>
@endif
<script>

    $(document).ready(function(){
        $(".tabla").DataTable({
            "language": {
                "url" : "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
            }
        });
        @if($errors->any())
        if(window.location.hash === '#create'){
            $('#modalAddUser').modal('show');
        }
        @endif

        $('#modalAddUser').on('hide.bs.modal',function(){
            window.location.hash = '#';
        });
        $('#modalAddUser').on('shown.bs.modal',function(){
            $('#user_name').focus();
            //window.location.hash = '#create';
        });

        $('#photo').change(function(){
            var imagen = this.files[0];
            console.log(imagen);

            if (imagen['type'] != 'image/jpeg' && imagen['type'] != 'image/png'){
                $(this).val('');

                Swal.fire({
                    type: 'error',
                    title: 'Error al subir la imagen',
                    text: 'La imagen debe tener el formato jpeg o PNG!',
                    confirmButtonText:"Cerrar"
                })
            }
            else if (imagen['size'] > 2000000){
                $(this).val('');

                Swal.fire({
                    type: 'error',
                    title: 'Error al subir la imagen',
                    text: 'La imagen no debe pesar mas de 2 Mb!',
                    confirmButtonText:"Cerrar"
                })
            }
            else{
                var datosImage = new FileReader;
                datosImage.readAsDataURL(imagen);
                $(datosImage).on('load', function(event){
                    var rutaImg = event.target.result;
                    $('#user_image').attr('src', rutaImg);
                });
            }
        });

        $('#btnCreate').on('click', function(){
            $('#user_oper').val('create');
            fnclearform();
            $('#modalAddUser').modal('show');
        })

        $('table tbody').on('click', '.btnActive', function(){
            var self = this;
            var id = $(self).data('id');
            var active =$(self).data('active');

            var token = $('#_token').val();
            $.ajax({
                url : '{{ route('admin.user.active', 'id') }}'.replace('id', id),
                method : 'POST',
                data : JSON.stringify({ active : active }),
                dataType : 'json',
                contentType: 'application/json',
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': token
                },
                success: function(result){
                    if(!result.error){
                        $(self).data('active', result.message);
                        if(result.message){
                            $(self).text('Activo');
                            $(self).removeClass('btn-danger');
                            $(self).addClass('btn-success');
                        }else{
                            $(self).text('No Activo');
                            $(self).removeClass('btn-success');
                            $(self).addClass('btn-danger');
                        }

                    }
                }

            });

        });
    });

    function fnclearform(){
        $('.form-group span.label').text('');
        $('.form-group input').val('');
        $('.form-group select').val('');
        $('#user_image').attr('src', '{{ asset('/storage/anonymous_user.png')}}');
    }

    function editUser(id){
        fnclearform();
        //--traer data
        $.ajax({
           url: "{{ route('admin.user.edit', 'id') }}".replace('id', id),
           method: 'GET',
            dataType: 'json',
            success:function(result){
                var data = result.data;
                console.log(result);
                $('#user_id').val(data.id);
                $('#user_name').val(data.name);
                $('#user_email').val(data.email);
                $('#user_role').val(data.role);
                $('#user_image').attr('src', data.foto);
                $('#user_oper').val('edit');
                $('#modalAddUser').modal('show');

            },
            statusCode:{
                403: function(result){
                    Swal.fire({
                        type: 'warning',
                        title: 'Alerta',
                        text: 'Acceso no autorizado',
                    });
                }
            }
        });


    }

    function saveUser(event){
        var accion = $('#user_oper').val();
        var url = '{{route('admin.user.store')}}';
        var midata = new FormData();
        if(accion == 'edit'){
            url = '{{ route('admin.user.update', 'id')}}'.replace('id', $('#user_id').val());
            midata.append('_method','PUT');
        }
        var token = $('#_token').val();
        midata.append('name',$('#user_name').val());
        midata.append('email', $('#user_email').val());
        midata.append('password',  $('#user_password').val());
        midata.append('role',  $('#user_role').val());
        midata.append('photo', $('#photo')[0].files.length > 0 ? $('#photo')[0].files[0]:'' )
        $.ajax({
            url : url,
            method : 'POST',
            data :  midata,
            dataType : 'json',
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': token
            },
            success: function(result){
                if (!result.error){
                    Swal.fire({
                        type: 'success',
                        title: 'Exito',
                        text: 'El usuario se guardo con exito',
                        onClose: () => {
                            !result.redirect ? location.reload() : location.href = result.redirect;
                        }
                    });

                    $('#modalAddUser').modal('hide');
                }
                else{
                    $('#modalAddUser').modal('hide');
                    Swal.fire({
                        type: 'warning',
                        title: 'Alerta',
                        text: result.message,

                    });
                }
            },
            error :function(result){
                var data = $.parseJSON(result.responseText);
                $.each(data.messages, function(key, value){
                    console.log(key)
                    $.each(value, function(index, item){
                        $('#sp_' + key).text(item);
                    });

                });

            }
        });

        return false;

    }

    function fndeleteUser(self, id){
        var username = $(self).data('user');
        Swal.fire({
        title: 'Desea eliminar este registro?',
        text: username,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si!',
        cancelButtonText: 'No, cancel!',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url : '{{route('admin.user.destroy', '#id')}}'.replace('#id', id),
                    method : 'DELETE',
                    dataType : 'json',
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('#_token').val()
                    },
                    success: function(result){
                        if (!result.error){
                            Swal.fire({
                                type: 'success',
                                title: 'Exito',
                                text: result.message,
                                onClose: () => {
                                    //$(self).parents("tr").remove();
                                    !result.redirect ? location.reload() : location.href = result.redirect;
                                }
                            });
                        }else{
                            Swal.fire({
                                type: 'warning',
                                title: 'Alerta',
                                text: result.message,
                            });
                        }
                    },
                    statusCode:{
                        403: function(result){
                            Swal.fire({
                                type: 'warning',
                                title: 'Alerta',
                                text: 'Acceso no autorizado',
                            });
                        }
                    }
                });

            }else
                return false;
        })

        return false;
    }


</script>
@endpush
