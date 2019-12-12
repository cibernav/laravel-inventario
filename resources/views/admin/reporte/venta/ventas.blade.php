@extends('admin.layout')

@section('title', 'Reporte de ventas')
@section('description', '')
@section('content')
<!-- Default box -->
<div class="box">
    <div class="box-header with-border">
        <input type="hidden" id="fechainicial">
        <input type="hidden" id="fechafinal">
        <button type="button" class="btn btn-default" id="btnFechaRango">
            <span>
                <i class="fa fa-calendar"></i> Rango de fecha
                <i class="fa fa-caret-down"></i>
            </span>
        </button>
        <div class="box-tools pull-right">
        <a href="#" onclick="return fnDownloadCsv();">
                <button class="btn btn-success" style="margin-top:5px">
                    Descargar reporte en Excel
                </button>
            </a>

        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-xs-12">
                {{-- ventas por año --}}
                @include('admin.reporte.venta.graficoventa')
            </div>

            <div class="col-md-6 col-xs-12">
                {{-- productos mas vendidos --}}
                @include('admin.reporte.venta.producto', ['products' => $products])
            </div>

            <div class="col-md-6 col-xs-12">
                {{-- mejores vendedores --}}
                @include('admin.reporte.venta.vendedores', ['vendedor' => $vendedor])
            </div>
            <div class="col-md-6 col-xs-12">
                {{-- mejores compradores --}}
                @include('admin.reporte.venta.compradores', ['comprador' => $comprador])
            </div>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        Footer
    </div>
    <!-- /.box-footer-->
</div>
<!-- /.box -->
@endsection

@push('scripts')
<script>
$(document).ready(function(){
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

            $('#fechainicial').val(start.format('YYYY-MM-DD'));
            $('#fechafinal').val(end.format('YYYY-MM-DD'));

            getDataChart();
        }
    );
});

function fnDownloadCsv(){
    //window.location = '{{route('admin.reporte.downloadcsv')}}'+ '?fechaini=' + $('#fechainicial').val() + '&fechafin=' + $('#fechafinal').val();
    window.open('{{route('admin.reporte.downloadcsv')}}'+ '?fechaini=' + $('#fechainicial').val() + '&fechafin=' + $('#fechafinal').val(), '_blank');
}
</script>
@endpush
