@extends('admin.layout')

@section('title', 'Administrar categoria')
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
        <button id="btnCreate" class="btn btn-primary">Agregar categoria</button>
    </div>
    <div class="box-body">
        <table class="table table-border table-striped tabla dt-responsive" width="100%">
            <thead>
                <tr>
                    <th class="col-xs-1">#</th>
                    <th>Descripcion</th>
                    <th class="col-xs-3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                <tr>
                    <td>{{$item->id}}</td>
                    <td>{{$item->name}}</td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-warning btn-xs" onclick="return fnEditItem({{ $item->id }});" ><i class="fa fa-pencil"></i></button>
                            @can('delete', $item)
                            <button type="button" class="btn btn-danger btn-xs" data-name="{{ $item->name }}" onclick="return fnDeleteItem(this, {{ $item->id }});"><i class="fa fa-times"></i></button>
                            @endcan
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
<div class="modal fade" id="modalAddCategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <form action="{{ route('admin.category.store')}}" method="POST" id="categoryform">
                <input type="hidden" value="{{ csrf_token() }}" name="_token" id="_token">
                <input type="hidden" value="create" id="operation">
                <input type="hidden" value="" id="category_id">
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Agregar categoria</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <div class="input-group {{ $errors->has('name') ? 'has-error' : '' }} ">
                                <span class="input-group-addon"><i class="fa fa-train"></i></span>
                                <input type="text" class="form-control input-lg" name="name" id="category_name" value="{{ old('name') }}" placeholder="Ingresar nombre" autofocus>
                                <span id="sp_name" class="label label-danger"></span>
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
            }
        });

        $('#modalAddCategory').on('hide.bs.modal',function(){
            window.location.hash = '#';
        });
        $('#modalAddCategory').on('shown.bs.modal',function(){
            $('#category_name').focus();
            //window.location.hash = '#create';
        });

        $('#btnCreate').on('click', function(){
            $('#operation').val('create');
            fnclearform();
            $('#modalAddCategory').modal('show');
        });

    });

function fnclearform(){
    $('.form-group span .label').text('');
    $('.form-group input').val('');
    $('.form-group select').val('');
    $('#user_image').attr('src', '{{ url('/storage/anonymous.png')}}');
}

    function fnEditItem(id){
        fnclearform();
        //--traer data
        $.ajax({
           url: "{{ route('admin.category.edit', '#id') }}".replace('#id', id),
           method: 'GET',
            dataType: 'json',
            success:function(result){
                var data = result.data;
                console.log(result);
                $('#category_id').val(data.id);
                $('#category_name').val(data.name);
                $('#operation').val('edit');
                $('#modalAddCategory').modal('show');

            }
        });


    }

    function fnSaveItem(event){
        var accion = $('#operation').val();
        var url = '{{route('admin.category.store')}}';
        var midata = new FormData();
        if(accion == 'edit'){
            url = '{{ route('admin.category.update', '#id')}}'.replace('#id', $('#category_id').val());
            midata.append('_method','PUT');
        }
        var token = $('#_token').val();
        midata.append('name',$('#category_name').val());

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
                            !result.redirect ? location.reload() : location.href = result.redirect;
                        }
                    });

                    $('#modalAddCategory').modal('hide');
                }
                else{
                    $('#modalAddCategory').modal('hide');
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
                    url : '{{route('admin.category.destroy', '#id')}}'.replace('#id', id),
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
                                    !result.redirect ? location.reload() : location.href = result.redirect;
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


</script>
@endpush
