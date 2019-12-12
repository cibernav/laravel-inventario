@extends('admin.layout')

@section('title', 'Editar venta')
@section('description', '')
@section('content')
<!-- Default box -->

<div class="row">
    <!-- Formulario -->
    <div class="col-lg-5 col-xs-12">
        <div class="box box-success">
            <div class="box-header with-border">
                <form method="POST" action="" id="frmVenta">
                <input type="hidden" value="{{ csrf_token() }}" name="_token" id="_token">
                <input type="hidden" value="{{ $dataventa->id_user }}" name="_username" id="_username">
                <input type="hidden" value="{{ $dataventa->id }}" id="idventa">
                    <div class="box-body">
                        <div class="box">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" id="name_user" name="name_user" value="{{ $dataventa->user->name }}"  readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                        <input type="text" class="form-control" id="serie_doc" name ="serie_doc" value ="{{ $dataventa ? $dataventa->serie : '1' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-8">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                        <input type="text" class="form-control" id="num_doc" name="num_doc" value="{{ $dataventa->numero }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-users"></i></span>
                                    <select id="id_cliente" class="form-control" name="id_cliente">
                                        <option value="">Seleccionar cliente</option>
                                        @foreach ($dataclient as $item)
                                        <option {{ $dataventa->id_cliente == $item->id ? 'selected' : 'disabled' }} value="{{ $item->id }}">{{ $item->nombre }}</option>
                                        @endforeach

                                    </select>
                                    <span class="input-group-addon">
                                        <button type="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modalAddCliente" data-dismiss="modal">Agregar cliente</button>
                                    </span>
                                </div>
                            </div>
                            {{-- <div class="form-group row">
                                <!-- Producto -->
                                <div class="col-xs-6" style="padding-right:0px">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <button type="button" class="btn btn-danger btn-xs">
                                                    <i class="fa fa-times"></i>
                                            </button>
                                        </span>
                                        <input type="text" class="form-control" id="nombre_producto" placeholder="Descripcion del producto">
                                    </div>
                                </div>
                                <!-- Cantidad -->
                                <div class="col-xs-3">
                                    <input type="number" class="form-control" min="1" placeholder="0" id="cantidad">
                                </div>
                                <!-- Precio -->
                                <div class="col-xs-3" style="padding-left:0px">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>
                                        <input type="number" class="form-control" min="1" placeholder="0.00" id="precio" readonly>
                                    </div>
                                </div>
                            </div> --}}
                            <!-- Boton mostrar agregar producto -->
                            <div class="row hidden-lg">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input type="text" class="form-control" data-id="0" data-precio="0" data-stock="0" id="search_producto" placeholder="Descripcion del producto">
                                            <span class="input-group-addon">
                                                <button type="button" class="btn btn-default btn-xs" id="btnAddProducto" >Agregar producto</button>
                                            </span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div id="listaProduct">
                            </div>
                            <hr>
                            {{-- Impuesto y total --}}
                            <div class="row">
                                <div class="col-xs-8 pull-right">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Impuesto</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td style="width: 50%">
                                                    <div class="input-group">
                                                    <input type="number" class="form-control" min="0" id="impuesto" name="impuesto" placeholder="0" value="{{ number_format(($dataventa->igv * 100)/$dataventa->subtotal , 2) }}">
                                                        <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                                                    </div>
                                                </td>
                                                <td style="width: 50%">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>
                                                    <input type="text" class="form-control numeroformato" data-total="{{ $dataventa->total }}" data-subtotal="{{ $dataventa->subtotal }}" data-impuesto="{{ $dataventa->igv }}" id="totalventa" name="totalventa" value="{{ $dataventa->total }}" placeholder="0.00" readonly>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            {{-- Metodo de pago --}}
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="form-group">
                                    <select id="metodo_pago" class="form-control">
                                            <option value="">Seleccione metodo de pago</option>
                                            <option {{ $dataventa->id_metodopago==1 ? 'selected' : '' }} value="1">Efectivo</option>
                                            <option {{ $dataventa->id_metodopago==2 ? 'selected' : '' }} value="2">Tarjeta Credito</option>
                                            <option {{ $dataventa->id_metodopago==3 ? 'selected' : '' }} value="3">Tarjeta Debito</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="opcionMetodoPago">

                                </div>
                                <div class="col-xs-6 hidden" style="padding-left:0px">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="codigotrasaccion" placeholder="Codigo transaccion">
                                        <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>

                    <div class="box-footer">
                    <a href="{{ route('admin.venta.index') }}" class="btn btn-default" id="btnCancelar">Cancelar</a>
                        <button type="submit" class="btn btn-primary pull-right" id="btnSaveVenta">Actualizar venta</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabla productos -->
    <div class="col-lg-7 hidden-md hidden-sm hidden-xs">
        <div class="box box-warning">
            <div class="box-header with-border"></div>
            <div class="box-body">
                <div class="row">
                    <div class="form-group">
                        <label class="col-md-3 text-center">Categoria</label>
                        <div class="col-md-6">
                            <select class="form-control" id="categoriaid">
                                <option>Seleccione categoria</option>
                                @foreach ($datacategory as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div clas="box-body table-responsive no-padding">
                            <table class="table table-hover tabla dt-responsive">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Imagen</th>
                                        <th>Codigo</th>
                                        <th>Descripcion</th>
                                        <th>Stock</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
{{-- <script src="{{ asset('/adminlte/js/typeahead.bundle.min.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.js"></script>
<script src="{{ asset('/adminlte/js/jquery.number.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.9.1/underscore-min.js"></script>
<script>
var dataListProduct = [];
var dataListProductRemove = [];
var table;
var metogopagochange;
$(document).ready(function(){
    //getNumero();

    table = $(".tabla").DataTable({
        "language": {
            "url" : "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
        },
        paging: true,
        processing: true,
        serverSide: true,
        autoWidth: false,
        columns:[
            { name: "id", data: "id" },
            { name: "imagen" },
            { name: "codigo", data: "codigo" },
            { name: "descripcion", data: "descripcion" },
            { name: "stock", data: "stockbtn" },
            { name: "precio_venta", data: "precio_venta" },
            { name: "acciones" }
        ],
        columnDefs:[
            { targets:[1], render:function(data, type, row){
                return `<img src="${row.imagen}" class="img-thumbnail" width="40px">`;
            }},
            { targets:[4], className: 'text-center' },
            { targets:[5], visible : false, searchable: false},
            { targets:[5], visible : false},
            { targets:[6], searchable : false, orderable: false, render: function(data, type, row){
                return `
                <div class="btn-group">
                    <button type="button" class="btn btn-primary btn-sm" onclick="return fnAddItem(${row.id}, '${row.descripcion}', ${row.precio_venta}, ${row.stock})">Agregar</button>
                </div>
                `;
            }}

        ],
        ajax:{
            url : '{{ route('admin.venta.listproduct') }}',
            data : function(d){
                d.idcategory = $('#categoriaid').val();
            },
            error : function(r){
                console.log(r);
            }
        }

    });

    $('#categoriaid').on('change', function(){
        table.ajax.reload();
    });

    $('#impuesto').on('change', function(){
        fncalcularTotal();
    });

    $('#btnAddProducto').on('click', function(){
        console.log('click');
        var self = $('#search_producto');
        var item = $(self).val();
        console.log(item.length);
        if (item.length > 0){
            var id = $(self).data('id');
            var item = $(self).val();
            var price = $(self).data('precio');
            var stock = $(self).data('stock');
            $(self).val('');
            //$('#listaProduct').append(addRow(id, item, price, 1));
            fnAddItem(id, item, price, stock);
        }
    });

    $('#search_producto').typeahead({
        minLength : 1,
        highlight: true,
        hint: true,
        displayText: function(item) {
            return item.descripcion;
        },
        afterSelect: function(item) {
            //this.$element[0].data = item.id;
            console.log(item);
            $('#search_producto').data('id', item.id);
            $('#search_producto').data('precio', item.precio);
            $('#search_producto').data('stock', item.stock);
        },
        source: function (query, result) {
            $.ajax({
                url: "{{ route('admin.venta.searchproduct') }}"+ '?query='+ query,
                dataType: "json",
                type: "GET",
                success: function (data) {
                    result($.map(data.data, function (item) {
                        console.log(item);
                        return item;
                    }));
                }
            });
        }
    });

    $('#search_producto').on('keypress', function(e){
        if(e.which == 13){
        }
    });

    $('.numeroformato').number(true, 2);

    metogopagochange= $('#metodo_pago').on('change', function(e){
        var value = this.value;
        if (value == '1'){
            $(this).parent().parent().removeClass('col-xs-6');
            $(this).parent().parent().addClass('col-xs-4');

            $('#opcionMetodoPago').html(addMetodoPago(1));
            $('.numeroformato').number(true, 2);
        }else{
            $(this).parent().parent().removeClass('col-xs-4');
            $(this).parent().parent().addClass('col-xs-6');
            $('#opcionMetodoPago').html(addMetodoPago(2));
        }

        console.log(this.value);
    });

    $('#opcionMetodoPago').on('change', 'input#importePagado', function(){
        var importePagado = Number($(this).val());
        var importeVuelto = importePagado - Number($('#totalventa').data('total'));
        $('#opcionMetodoPago input#importeVuelto').val(importeVuelto);
        console.log(importeVuelto);
    });

    $("form").on("keypress", function(e) {
        if (e.keyCode == 13) {
            return false;
        }
    });

    $('#frmVenta').submit(function(e){
        e.preventDefault();

        console.log($(this).serialize());

        if (dataListProduct.length == 0){
            Swal.fire({
                type: 'warning',
                title: 'Producto insuficiente',
                text: `Debe agregar mas articulos!`
            });
        }
        var detallepago = '';
        if ($('#metodo_pago').val() == '1')
            detallepago = $('#importePagado').val();
        else
            detallepago = $('#codigotrasaccion').val();

        var itemsArray = _.union(dataListProduct, dataListProductRemove);
        var lista = JSON.stringify(itemsArray);
        var formdata = new FormData();
        formdata.append('_method', 'PUT');
        formdata.append('id', $('#idventa').val());
        formdata.append('user', $('#name_user').val());
        formdata.append('serie', $('#serie_doc').val());
        formdata.append('numero', $('#num_doc').val());
        formdata.append('id_cliente', $('#id_cliente').val());
        formdata.append('impuesto', $('#totalventa').data('impuesto'));
        formdata.append('subtotal', $('#totalventa').data('subtotal'));
        formdata.append('totalventa', $('#totalventa').data('total'));
        formdata.append('id_metodopago', $('#metodo_pago').val());
        formdata.append('detallepago', detallepago);
        formdata.append('items', lista);

        console.log(lista);

        $.ajax({
            url: '{{ route('admin.venta.update', $dataventa->id) }}',
            type: 'POST',
            data: formdata,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('#_token').val()
            },
            success: function(result){
                if (!result.error){
                    Swal.fire({
                        type: 'success',
                        title: 'Completo',
                        text: 'La venta se registro con exito.',
                        onClose: function(){
                            !result.redirect ? location.reload() : location.href = result.redirect;
                        }
                    });


                }
                console.log(result);
            }

        });
    });

    fnEditLoad();

});

function fnEditLoad(){
    metogopagochange.change();
    fnLoadDetail();
    $('#importePagado').val({{ $dataventa->detallepago }});
    $('#codigotrasaccion').val({{ $dataventa->detallepago }});
}

function fnLoadDetail(){
    $.ajax({
        url: '{{ route('admin.venta.items', $dataventa->id) }}',
        type: 'GET',
        success: function(result){

            dataListProduct = result.data;
            console.log(dataListProduct);
            refreshRows(false);
        }
    });
}

function fnclearform(){
    $('#frmVenta .form-group span.label').text('');
    $('#frmVenta .form-group input').val('');
    $('#frmVenta .input-group input').val('');
    $('#frmVenta .form-group select').val('');
    $('#name_user').val($('#_username').val());
    $('#metodo_pago').val('');
    getNumero();

    dataListProduct = [];
    dataListProductRemove = [];
    refreshRows();
    table.ajax.reload();
}

function addMetodoPago(tipo){
    if (tipo == 1){
        return `
        <div class="col-xs-4" style="padding-left:0px">
            <div class="input-group">
                <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>
                <input type="text" class="form-control numeroformato" id="importePagado" placeholder="0.00">
            </div>
        </div>
        <div class="col-xs-4" style="padding-left:0px">
            <div class="input-group">
                <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>
                <input type="text" class="form-control numeroformato" id="importeVuelto" placeholder="0.00" readonly>
            </div>
        </div>
        `;
    }
    else{
        return `
        <div class="col-xs-6" style="padding-left:0px">
            <div class="input-group">
                <input type="text" class="form-control" id="codigotrasaccion" placeholder="Codigo transaccion">
                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
            </div>
        </div>
        `
    }
}

function getNumero(){
    $.ajax({
       url : '{{ route('admin.venta.numero', '#id') }}'.replace('#id', 1),
        method : 'GET',
        success : function(result){
            $('#serie_doc').val(1);
            $('#num_doc').val(result.data);
        }
    });
}

function fnAddItem(id, product, price, stock, tipo = 1){
    if (stock > 0){
        var item = dataListProduct.find(y => y.idproduct == id);
        var cant = 1;
        if (item){
            var newCant = cant + Number(item.cantidad);
            if (newCant > stock){
                item.cantidad = 1;
                Swal.fire({
                    type: 'warning',
                    title: 'La cantidad supera el Stock',
                    text: `Solo hay ${stock} unidades!`
                });
            }
            else{
                item.cantidad = newCant;
            }
        }
        else
            dataListProduct.push({id: 0, idproduct: id, product : product, price : price, cantidad : cant, stock: stock, tipo: tipo });

        refreshRows();

    }else{
        Swal.fire({
            type: 'warning',
            title: 'Alerta',
            text: 'No hay stock disponible.'
        });
    }

}

function refreshRows(nuevo = true){
    console.clear();
    $('#listaProduct').html('');
    dataListProduct.forEach(x => {
        var row = addRow(x.id, x.idproduct, x.product, x.price, x.cantidad, x.stock );
        $('#listaProduct').append(row);
        console.log(x);
    });

    if(nuevo)
        fncalcularTotal();


    $('.numeroformato').number(true, 2);
}

function addRow(id, idproduct, product, precio, cant, stock){
    var row = `
    <div class="form-group row">
        <!-- Producto -->
        <div class="col-xs-6" style="padding-right:0px">
            <div class="input-group">
                <span class="input-group-addon">
                    <button type="button" class="btn btn-danger btn-xs" data-id="${id}" data-idproduct="${idproduct}" onclick="return deleteRow(this);">
                            <i class="fa fa-times"></i>
                    </button>
                </span>
                <input type="text" class="form-control" id="nombre_producto" placeholder="Descripcion del producto" value="${product}" readonly>
            </div>
        </div>
        <!-- Cantidad -->
        <div class="col-xs-3">
            <input type="number" class="form-control" min="1" placeholder="0" id="cantidad" value="${cant}" data-idproduct="${id}" data-stock="${stock}" onchange="return calculateRow(this);">
        </div>
        <!-- Precio -->
        <div class="col-xs-3" style="padding-left:0px">
            <div class="input-group">
                <span class="input-group-addon"><i class="ion ion-social-usd"></i></span>
                <input type="text" class="form-control numeroformato" placeholder="0.00" id="precio" value="${precio}" readonly>
            </div>
        </div>
    </div>
    `;

    return row;

}

function deleteRow(self){
    console.log(self);
    var id = $(self).data('id');
    var idproduct = $(self).data('idproduct');

    dataListProduct = dataListProduct.filter(x => x.idproduct != idproduct);
    $(self).parent().parent().parent().parent().remove();
    console.log(dataListProduct);

    if (id > 0)
        dataListProductRemove.push({id: id, idproduct: 0, product : '', price : 0, cantidad : 0, stock: 0, tipo: 3 });

    fncalcularTotal();
}

function fncalcularTotal(){
    var total = 0;
    var subtotal = 0;
    var impuesto =  Number($('#impuesto').val()) / 100;
    dataListProduct.forEach(x => {
        subtotal += x.cantidad * x.price;
    });

    impuesto = (subtotal * impuesto);
    total =  impuesto + subtotal;
    $('#totalventa').val(total.toFixed(2));
    $('#totalventa').data('total', total);
    $('#totalventa').data('subtotal', subtotal);
    $('#totalventa').data('impuesto', impuesto);
}

function calculateRow(self){
    var id = $(self).data('idproduct');
    var cant = Number(self.value);
    var stock = Number($(self).data('stock'));
    console.log(self);
    console.log(id + '/' + cant + '/' + stock);
    var item = dataListProduct.find(y => y.idproduct == id);
    if(cant > stock){
        item.cantidad = 1;
        refreshRows();
        Swal.fire({
            type: 'warning',
            title: 'La cantidad supera el Stock',
            text: `Solo hay ${stock} unidades!`
        });
    }else{

        if (item)
            item.cantidad = cant;
        fncalcularTotal();
    }
    if(item.tipo == 4)
        item.tipo = 2;
}
</script>
@endpush
