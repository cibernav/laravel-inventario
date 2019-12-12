@extends('admin.layout')

@section('title', 'Administrar ventas')
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
    <a href="{{ route('admin.venta.create') }}" class="btn btn-primary">Agregar venta</a>
    <button type="button" class="btn btn-default pull-right" id="btnFechaRango">
        <span>
            <i class="fa fa-calendar"></i> Rango de fecha
            <i class="fa fa-caret-down"></i>
        </span>
    </button>
    </div>
    <div class="box-body">
    <input type="hidden" id="_token" value="{{ csrf_token() }}">
        <table class="table table-border table-striped tabla dt-responsive" width="100%">
            <thead>
                <tr>
                    <th >#</th>
                    <th>Serie</th>
                    <th>Numero</th>
                    <th>Cliente</th>
                    <th>Vendedor</th>
                    <th>Forma de Pago</th>
                    <th>SubTotal</th>
                    <th>Total</th>
                    <th>Fecha</th>
                    <th class="col-xs-1">Acciones</th>
                </tr>
            </thead>
            <tbody>
                {{-- @foreach ($data as $item)
                <tr>
                    <td>{{$item->id}}</td>
                    <td>{{$item->serie . '-' . $item->numero}}</td>
                    <td>{{$item->cliente}}</td>
                    <td>{{$item->vendedor}}</td>
                    <td>{{$item->metodopago}}</td>
                    <td>{{$item->subtotal}}</td>
                    <td>{{$item->total}}</td>
                    <td>{{$item->fechaemision}}</td>
                    <td>
                        <div class="btn-group">
                            <a class="btn btn-success btn-xs" data-id="{{ $item->id }}" onclick="fnImprimir(this);" ><i class="fa fa-print"></i></a>
                            <a class="btn btn-warning btn-xs" href="{{ route('admin.venta.edit', $item->id) }}"><i class="fa fa-pencil"></i></a>
                            <button type="button" class="btn btn-danger btn-xs" data-name="{{ $item->name }}" onclick="return fnDeleteItem(this, {{ $item->id }});"><i class="fa fa-times"></i></button>
                        </div>
                    </td>
                </tr>
                @endforeach --}}
            </tbody>
        </table>

    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->

@endsection


@push('scripts')

<script>
var rutaEdit = '{{ route('admin.venta.edit', '##') }}';
var fechaInicio = '';
var fechaFin = '';
    $(document).ready(function(){
        fnIniBandeja();

        const bandeja = $(".tabla").DataTable({
            "language": {
                "url" : "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
            },
            paging: true,
            processing: true,
            serverSide: true,
            columns:[
                { name: 'id', data: 'id'},
                { name: 'serie', data: 'serie'},
                { name: 'numero', data: 'numero'},
                { name: 'cliente', data: 'cliente'},
                { name: 'vendedor', data: 'vendedor'},
                { name: 'id_metodopago', data: 'metodopago'},
                { name: 'subtotal', data: 'subtotal'},
                { name: 'total', data: 'total'},
                { name: 'fechaemision', data: 'fechaemision'},
                { name: 'acciones', defaultContent: '' }
            ],
            columnDefs :[
                { targets:[9], orderable: false, searchable: false, render: function(data, type, row){
                  return `
                    <div class="btn-group">
                        <a class="btn btn-success btn-xs" data-id="${row.id}" onclick="fnImprimir(this);" ><i class="fa fa-print"></i></a>` +
                        @can('update', $data)
                        `<a class="btn btn-warning btn-xs" href="#" onclick="fnEditItem(this, ${row.id});" ><i class="fa fa-pencil"></i></a>` +
                        @endcan
                        @can('delete', $data)
                        `<button type="button" class="btn btn-danger btn-xs" data-name="${row.serie + '-' + row.numero}" onclick="return fnDeleteItem(this, ${row.id});"><i class="fa fa-times"></i></button>` +
                        @endcan
                    `</div>`;
                }}
            ],
            ajax:{
                url : '{{ route('admin.venta.listarbandeja') }}',
                data: function(d){
                    d.fechainicio = fechaInicio,
                    d.fechafin = fechaFin
                },
                error: function(e){
                    console.log(e);
                }
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

        moment.locale('es');
        //Date range as a button
        $('#btnFechaRango').daterangepicker(
            {
                "locale": {
                    "format": "DD/MM/YYYY",
                    "separator": " - ",
                    "applyLabel": "Aplicar",
                    "cancelLabel": "Cancelar",
                    "fromLabel": "De",
                    "toLabel": "Hasta",
                    "customRangeLabel": "Otro Rango",
                    "weekLabel": "W",
                    "daysOfWeek": [
                        "Dom",
                        "Lun",
                        "Mar",
                        "Mie",
                        "Jue",
                        "Vie",
                        "Sab"
                    ],
                    "monthNames": [
                        "Enero",
                        "Febrero",
                        "Marzo",
                        "Abril",
                        "Mayo",
                        "Junio",
                        "Julio",
                        "Agosto",
                        "Septiembre",
                        "Octubre",
                        "Noviembre",
                        "Diciembre"
                    ],
                    "firstDay": 0
                },
                ranges : {
                    'Hoy'       : [moment(), moment()],
                    'Ayer'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Ultimos 7 dias' : [moment().subtract(6, 'days'), moment()],
                    'Ultimos 30 dias': [moment().subtract(29, 'days'), moment()],
                    'Este mes'  : [moment().startOf('month'), moment().endOf('month')],
                    'Ultimo mes'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment(), //.subtract(29, 'days'),
                endDate  : moment()
            },
            function (start, end) {
                $('#btnFechaRango span').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));

                console.log(start.format('YYYY-MM-DD'));
                console.log(end.format('YYYY-MM-DD'));

                fechaInicio = start.format('YYYY-MM-DD');
                fechaFin = end.format('YYYY-MM-DD');

                bandeja.ajax.reload();
            }
        );

    });

    function fnIniBandeja(){
        start = moment();
        end = moment();
        $('#btnFechaRango span').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
        fechaInicio = start.format('YYYY-MM-DD');
        fechaFin = end.format('YYYY-MM-DD');

    }
    function fnclearform(){
        $('.form-group span .label').text('');
        $('.form-group input').val('');
        $('.form-group select').val('');
        $('#user_image').attr('src', '{{ url('/storage/anonymous.png')}}');
    }

    function fnEditItem(self, id){
        var username = $(self).data('name');
        Swal.fire({
        title: 'Desea editar este registro?',
        text: username,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si!',
        cancelButtonText: 'No, cancel!',
        }).then(function(result){
            if(result.value){
                location.href = '{{ route('admin.venta.edit', '##') }}'.replace('##', id);
            }
        });
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
                    url : '{{route('admin.venta.destroy', '#id')}}'.replace('#id', id),
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
                        }
                    }
                });

            }else
                return false;
        })

        return false;
    }

    function fnImprimir(self){
        var id = $(self).data('id');
        console.log(id);
        window.open('{{ route('admin.venta.printer', '##') }}'.replace('##', id), '_blank');
    }

</script>
@endpush
