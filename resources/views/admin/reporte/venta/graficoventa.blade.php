
<div class="box box-solid bg-teal-gradient">
    <div class="box-header">
        <i class="fa fa-th"></i>
        <h3 class="box-title">Grafico de Ventas</h3>
    </div>
    <div class="box-body border-radius-none nuevoGraficoVentas">
        <div class="chart" id="line-chart" style="height: 250px">

        </div>
    </div>
</div>

@push('scripts')
<script>
var lineChart = null;
function getDataChart(){
    $.ajax({
        url: '{{ route('admin.reporte.chartventa') }}',
        method : 'GET',
        data : { fechaini : $('#fechainicial').val(), fechafin : $('#fechafinal').val() },
        success : function(result){
            console.log(result);
            lineChart.setData((result.data));
        }
    });
}
$(document).ready(function(){
    fnIniBandeja();

    function fnIniBandeja(){
        start = moment();
        end = moment();
        $('#btnFechaRango span').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
        $('#fechainicial').val(start.format('YYYY-MM-DD'));
        $('#fechafinal').val(end.format('YYYY-MM-DD'));
    }

    /* Morris.js Charts */
    lineChart = new Morris.Line({
        element          : 'line-chart',
        resize           : true,
        data             : [
            { y: '0', total: 0 }
        ],
        xkey             : 'y',
        ykeys            : ['total'],
        labels           : ['Ventas'],
        lineColors       : ['#efefef'],
        lineWidth        : 2,
        hideHover        : 'auto',
        gridTextColor    : '#fff',
        gridStrokeWidth  : 0.4,
        pointSize        : 4,
        pointStrokeColors: ['#efefef'],
        gridLineColor    : '#efefef',
        gridTextFamily   : 'Open Sans',
        gridTextSize     : 10,
        preUnits         : 'S/.',
        xLabels         : 'month'
    });



    getDataChart();
});


</script>
@endpush
