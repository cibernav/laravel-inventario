@extends('admin.layout')

@section('title', 'Administrar cliente')
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
        <button id="btnCreate" class="btn btn-primary">Agregar cliente</button>
    </div>
    <div class="box-body">
        <table class="table table-border table-striped tabla dt-responsive" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Doc. Ident.</th>
                    <th>Email</th>
                    <th>Telefono</th>
                    <th>Direccion</th>
                    <th>Fec.Nac.</th>
                    <th>Tot.Compra</th>
                    <th>Ult.Compra</th>
                    <th>Ing.Sistema</th>
                    <th class="col-xs-1">Acciones</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->

<!-- Modal -->
<div class="modal fade" id="modalAddCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <form action="" method="POST" id="myform">
                <input type="hidden" value="{{ csrf_token() }}" name="_token" id="_token">
                <input type="hidden" value="create" id="operation">
                <input type="hidden" value="" id="client_id">
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Agregar cliente</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control input-lg" name="nombre" id="client_nombre"  placeholder="Ingresar nombre" autofocus>
                                <span id="sp_nombre" class="label label-danger"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                <input type="number" min="0" class="form-control input-lg" name="documento" id="client_documento"  placeholder="Ingresar documento" >
                                <span id="sp_documento" class="label label-danger"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                <input type="email" class="form-control input-lg" name="email" id="client_email"  placeholder="Ingresar email" >
                                <span id="sp_email" class="label label-danger"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                <input type="text" class="form-control input-lg" name="telefono" id="client_telefono" data-inputmask="'mask': '(999) 999-9999'" data-mask  placeholder="Ingresar telefono" >
                                <span id="sp_telefono" class="label label-danger"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
                                <input type="text" class="form-control input-lg" name="direccion" id="client_direccion"  placeholder="Ingresar direccion" >
                                <span id="sp_direccion" class="label label-danger"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" class="form-control input-lg" name="fecha_nacimiento" id="client_fecha_nacimiento" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask  placeholder="Ingresar fecha nacimiento" >
                                <span id="sp_fecha_nacimiento" class="label label-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
                    <button type="submit"  class="btn btn-primary" onclick="return fnSaveItem(event);" >Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function(){
        $(".tabla").DataTable({
            "language": {
                "url" : "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
            },
            paging: true,
            processing: true,
            serverSide: true,
            columns:[
                { name: 'id', data: 'id'},
                { name: 'nombre', data: 'nombre'},
                { name: 'documento', data: 'documento'},
                { name: 'email', data: 'email'},
                { name: 'telefono', data: 'telefono'},
                { name: 'direccion', data: 'direccion'},
                { name: 'fecha_nacimiento', data: 'fecha_nacimiento'},
                { name: 'compras', data: 'compras'},
                { name: 'ulticompra' , data: 'ultima_compra' }, //defaultContent: ''
                { name: 'fecha', data: 'fecha'},
                { name: 'acciones' },

            ],
            columnDefs:[
                { targets:[7], className: 'text-center' },
                { targets:[9], className: 'text-center' },
                { targets: [8], orderable : false, searchable: false },
                { targets: [10], orderable : false, searchable: false, render:function(data, type, row){
                    return `
                    <div class="btn-group">
                        <button type="button" class="btn btn-warning btn-xs" onclick="return fnEditItem(${row.id});" ><i class="fa fa-pencil"></i></button>` +
                        @can('delete', $client)
                        `<button type="button" class="btn btn-danger btn-xs" data-name="${ row.nombre }" onclick="return fnDeleteItem(this, ${ row.id });"><i class="fa fa-times"></i></button>` +
                        @endcan
                    `</div>`;
                }}
            ],
            ajax : '{{ route('admin.client.list') }}'
        });

        $('#modalAddCliente').on('hide.bs.modal',function(){
            window.location.hash = '#';
        });
        $('#modalAddCliente').on('shown.bs.modal',function(){
            $('#client_nombre').focus();
            //window.location.hash = '#create';
        });

        $('#btnCreate').on('click', function(){
            $('#operation').val('create');
            fnclearform();
            $('#modalAddCliente').modal('show');
        });

    });

function fnclearform(){
    $('.form-group span.label').text('');
    $('.form-group input').val('');
    $('.form-group select').val('');
}

    function fnEditItem(id){
        fnclearform();
        //--traer data
        $.ajax({
           url: "{{ route('admin.client.edit', '#id') }}".replace('#id', id),
           method: 'GET',
            dataType: 'json',
            success:function(result){
                var data = result.data;
                console.log(result);
                $('#client_id').val(data.id);
                $('#client_nombre').val(data.nombre);
                $('#client_documento').val(data.documento);
                $('#client_email').val(data.email);
                $('#client_telefono').val(data.telefono);
                $('#client_direccion').val(data.direccion);
                $('#client_fecha_nacimiento').val(data.fecha_nacimiento);
                $('#operation').val('edit');
                $('#modalAddCliente').modal('show');

            }
        });


    }

    function fnSaveItem(event){
        $('.form-group span.label').text('');
        var accion = $('#operation').val();
        var url = '{{route('admin.client.store')}}';
        var midata = new FormData();
        if(accion == 'edit'){
            url = '{{ route('admin.client.update', '#id')}}'.replace('#id', $('#client_id').val());
            midata.append('_method','PUT');
        }
        var token = $('#_token').val();
        midata.append('nombre',$('#client_nombre').val());
        midata.append('documento',$('#client_documento').val());
        midata.append('email',$('#client_email').val());
        midata.append('telefono',$('#client_telefono').val());
        midata.append('direccion',$('#client_direccion').val());
        midata.append('fecha_nacimiento',$('#client_fecha_nacimiento').val());

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
                        text: result.message,
                        onClose: () => {
                            !result.redirect ? $('.tabla').DataTable().ajax.reload() : location.href = result.redirect;
                        }
                    });

                    $('#modalAddCliente').modal('hide');
                }
                else{
                    $('#modalAddCliente').modal('hide');
                    Swal.fire({
                        type: 'warning',
                        title: 'Alerta',
                        text: result.message,

                    });
                }
            },
            error :function(result){
                var data = $.parseJSON(result.responseText);
                $.each(data.message, function(key, value){
                    console.log(key)
                    $.each(value, function(index, item){
                        $('#sp_' + key).text(item);
                    });

                });

            }
        });

        return false;

    }

    function fnDeleteItem(self, id){
        var username = $(self).data('name');
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
                    url : '{{route('admin.client.destroy', '#id')}}'.replace('#id', id),
                    method : 'DELETE',
                    dataType : 'json',
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('#_token').val()
                    },
                    success: function(){
                        if (!result.error){
                            Swal.fire({
                                type: 'success',
                                title: 'Exito',
                                text: result.message,
                                onClose: () => {
                                    //$(self).parents("tr").remove();
                                    !result.redirect ? $('.tabla').DataTable().ajax.reload() : location.href = result.redirect;
                                }
                            });
                        }
                    }
                });

            }else
                return false;
        })

        return false;
    }

function fngenButton(){
    var button = '';
    @can('delete', $client)
    button = '<p>PRINT ERROR DELETE</p>';
    @endcan

    console.log(button);
}

</script>
@endpush
