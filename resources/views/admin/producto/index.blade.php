@extends('admin.layout')

@section('title', 'Administrar producto')
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
        <button id="btnCreate" class="btn btn-primary">Agregar producto</button>
    </div>
    <div class="box-body">
        <table class="table table-border table-striped tabla dt-responsive" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Imagen</th>
                    <th>Codigo</th>
                    <th>Descripcion</th>
                    <th>Categoria</th>
                    <th>Stock</th>
                    <th>Precio compra</th>
                    <th>Precio venta</th>
                    <th>Agregado</th>
                    <th>Acciones</th>
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
<div class="modal fade" id="modalAddProducto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <form action="{{ route('admin.product.store')}}" method="POST" id="categoryform" enctype="multipart/form-data">
                <input type="hidden" value="{{ csrf_token() }}" name="_token" id="_token">
                <input type="hidden" value="create" id="operation">
                <input type="hidden" value="" id="producto_id">
                <div class="modal-header" style="background:#3c8dbc; color:white">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Agregar producto</h4>
                </div>
                <div class="modal-body">
                    <div class="box-body">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-th"></i></span>
                                <select class="form-control input-lg" name = "categoria_id" id="categoria_id" onfocus>
                                    <option value="">Selecciona categoria</option>
                                    @foreach ($category as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                <span id="sp_categoria_id" class="label label-danger"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group {{ $errors->has('codigo') ? 'has-error' : '' }} ">
                                <span class="input-group-addon"><i class="fa fa-code"></i></span>
                                <input type="text" class="form-control input-lg" name="codigo" id="producto_codigo" placeholder="Ingresar codigo" readonly>
                                <span id="sp_codigo" class="label label-danger"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span>
                                <input type="text" class="form-control input-lg" name="descripcion" id="producto_descripcion" placeholder="Ingresar descripcion">
                                <span id="sp_descripcion" class="label label-danger"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group ">
                                <span class="input-group-addon"><i class="fa fa-check"></i></span>
                                <input type="number" class="form-control input-lg" name="stock" id="producto_stock" min="0" placeholder="Stock">
                                <span id="sp_stock" class="label label-danger"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa  fa-arrow-up"></i></span>
                                    <input type="number" class="form-control input-lg" name="precio_compra" id="producto_precio_compra" min="0" step="0.01" placeholder="Precio de compra">
                                    <span id="sp_precio_compra" class="label label-danger"></span>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa  fa-arrow-down"></i></span>
                                    <input type="number" class="form-control input-lg" name="precio_venta" id="producto_precio_venta" min="0" step="0.01" placeholder="Precio de venta">
                                    <span id="sp_precio_venta" class="label label-danger"></span>
                                </div>
                            </div>

                        </div>
                        <div class="form-group row">
                            <div class="col-xs-offset-6">
                                <div class="col-xs-5">
                                    <label>
                                        <input type="checkbox" id="producto_checkbox" checked >
                                        Utilizar porcentaje
                                    </label>

                                </div>
                                <div class="col-xs-7">
                                    <div class="input-group">
                                        <input type="number" class="form-control input-lg" name="porcentaje" id="producto_porcentaje" min="0" value="40">
                                        <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                                        <span id="sp_porcentaje" class="label label-danger"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                                <div class="panel">
                                    SUBIR IMAGEN
                                </div>
                                <input type="file" name="photo" id="producto_photo" class="form-control">
                                <p class="help-block">Peso maximo de la foto 2 MB</p>
                                <img class="img-bordered-sm" id="producto_image" src="{{ asset('/storage/anonymous_prod.png')}}" width="140px">
                                <span id="sp_photo" class="label label-danger"></span>
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
                { name: "id", data: "id" },
                { name: "imagen" },
                { name: "codigo", data: "codigo" },
                { name: "descripcion", data: "descripcion" },
                { name: "categories.name", data: "categoria" },
                { name: "stock", data: "stock" },
                { name: "precio_compra", data: "precio_compra" },
                { name: "precio_venta", data: "precio_venta" },
                { name: "fecha", data: "fecha" },
                { name: "acciones" }
            ],
            columnDefs:[
                { targets:[1], render:function(data, type, row){
                    return `<img src="${row.imagen}" class="img-thumbnail" width="40px">`;
                }},
                { targets:[5], className: 'text-center' },
                { targets:[9], searchable : false, orderable: false, render: function(data, type, row){
                    return `
                    <div class="btn-group">
                        <button type="button" class="btn btn-warning btn-xs" onclick="return fnEditItem(${row.id});" ><i class="fa fa-pencil"></i></button>` +
                        @can('delete', $data)
                        `<button type="button" class="btn btn-danger btn-xs" data-name="${ row.codigo }" onclick="return fnDeleteItem(this, ${ row.id });"><i class="fa fa-times"></i></button>` +
                        @endcan
                    '</div>';
                }}
            ],
            ajax: '{{ route('admin.product.list') }}'

        });

        $('#modalAddProducto').on('hide.bs.modal',function(){
            window.location.hash = '#';
        });
        $('#modalAddProducto').on('shown.bs.modal',function(){
            $('#producto_descripcion').focus();
        });

        $('#btnCreate').on('click', function(){
            $('#operation').val('create');
            fnclearform();
            $('#modalAddProducto').modal('show');
        });



        $('#producto_photo').change(function(){
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
                    $('#producto_image').attr('src', rutaImg);
                });
            }
        });

        $('#categoria_id').on('change', function(){
            var id = $(this).val();
            $.ajax({
                    url : '{{route('admin.product.codigo', '#id')}}'.replace('#id', id),
                    method : 'GET',
                    dataType : 'json',
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('#_token').val()
                    },
                    success: function(result){
                        if (!result.error){
                            $('#producto_codigo').val(result.data);
                        }
                    }
                });
        });

        $('#producto_precio_compra').on('change', function(){
            fncalcularPrecio(this, $('#producto_porcentaje'));
        });

        $('#producto_porcentaje').on('change', function(){
            fncalcularPrecio($('#producto_precio_compra'), this);
        });

        $('#producto_checkbox').on('ifChanged', function(e){
            console.log(e.target.checked);
            $('#producto_precio_venta').prop('readonly', e.target.checked);
            if(e.target.checked)
                fncalcularPrecio($('#producto_precio_compra'), $('#producto_porcentaje'));
        });

    });

    function fncalcularPrecio(precio_compra, porcentaje){
        if($('#producto_checkbox').prop('checked')){
            var precioCompra = Number($(precio_compra).val());
            var valorPorcentaje = Number($(porcentaje).val());
            var porcentaje = ((precioCompra * valorPorcentaje)/100) + precioCompra;
            console.log(precioCompra + ' / ' + valorPorcentaje);
            $('#producto_precio_venta').val(porcentaje);
            $('#producto_precio_venta').prop('readonly', true);
        }
    }

    function fnclearform(){
        $('.form-group span.label').text('');
        $('.form-group input').val('');
        $('.form-group select').val('');
        $('#producto_image').attr('src', '{{ asset('/storage/anonymous_prod.png')}}');
        $('#producto_porcentaje').val(40);
    }

    function fnEditItem(id){
        fnclearform();
        //--traer data
        $.ajax({
           url: "{{ route('admin.product.edit', '#id') }}".replace('#id', id),
           method: 'GET',
            dataType: 'json',
            success:function(result){
                var data = result.data;
                console.log(result);
                $('#producto_id').val(id);
                $('#producto_codigo').val(data.codigo);
                $('#producto_descripcion').val(data.descripcion);
                $('#categoria_id').val(data.categoria_id);
                $('#producto_image').attr('src', data.imagen);
                $('#producto_stock').val(data.stock);
                $('#producto_precio_compra').val(data.precio_compra);
                $('#producto_precio_venta').val(data.precio_venta);
                $('#operation').val('edit');
                $('#modalAddProducto').modal('show');

            }
        });


    }

    function fnSaveItem(event){
        //event.preventDefault();
        $('.form-group span.label').text('');

        var accion = $('#operation').val();
        var url = '{{route('admin.product.store')}}';
        var midata = new FormData();
        if(accion == 'edit'){
            url = '{{ route('admin.product.update', '#id')}}'.replace('#id', $('#producto_id').val());
            midata.append('_method','PUT');
        }
        var token = $('#_token').val();
        midata.append('codigo',$('#producto_codigo').val());
        midata.append('descripcion',$('#producto_descripcion').val());
        midata.append('categoria_id',$('#categoria_id').val());
        midata.append('imagen', $('#producto_photo')[0].files.length > 0 ? $('#producto_photo')[0].files[0]: '');
        midata.append('stock',$('#producto_stock').val());
        midata.append('precio_compra',$('#producto_precio_compra').val());
        midata.append('precio_venta',$('#producto_precio_venta').val());

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

                    $('#modalAddProducto').modal('hide');
                }
                else{
                    $('#modalAddProducto').modal('hide');
                    Swal.fire({
                        type: 'warning',
                        title: 'Alerta',
                        text: result.message
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
                    url : '{{route('admin.product.destroy', '#id')}}'.replace('#id', id),
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


</script>
@endpush
