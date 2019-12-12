<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">Vendedores</h3>
    </div>
    <div class="box-body">
        <div class="chart-responsive">
            <div class="chart" id="bar_chart1" style="height: 300px"></div>
        </div>
    </div>
</div>
@push('scripts')
<script>

$(document).ready(function(){
    //BAR CHART
    var bar = new Morris.Bar({
        element: 'bar_chart1',
        resize: true,
        data: [
        @foreach($vendedor as $item)
        {x: '{{$item->nombre}}', total:{{ $item->total}} },
        @endforeach
        ],
        barColors: ['#0af'],
        xkey: 'x',
        ykeys: ['total'],
        labels: ['Ventas'],
        hideHover: 'auto',
        preUnits: 'S/.'
    });
})

</script>
@endpush
