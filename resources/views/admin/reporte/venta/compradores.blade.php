<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Compradores</h3>
    </div>
    <div class="box-body">
        <div class="chart-responsive">
            <div class="chart" id="bar_chart2" style="height: 300px"></div>
        </div>
    </div>
</div>
@push('scripts')
<script>
$(document).ready(function(){
    //BAR CHART
    var bar = new Morris.Bar({
        element: 'bar_chart2',
        resize: true,
        data: [
        @foreach($comprador as $item)
        {x: '{{$item->nombre}}', total: {{$item->total}} },
        @endforeach
        ],
        barColors: ['#f6a'],
        xkey: 'x',
        ykeys: ['total'],
        labels: ['Ventas'],
        hideHover: 'auto',
        preUnits: 'S/.'
    });
})


</script>
@endpush
