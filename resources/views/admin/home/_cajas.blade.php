<div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-aqua">
        <div class="inner">
        <h3>S/. {{ $total['venta']}}</h3>

        <p>Ventas</p>
        </div>
        <div class="icon">
        <i class="ion ion-social-usd"></i>
        </div>
    <a href="{{ route('admin.venta.index') }}" class="small-box-footer">Mas info <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
<!-- ./col -->
<div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-green">
        <div class="inner">
        <h3>{{ $total['categoria']}}</sup></h3>

        <p>Categorias</p>
        </div>
        <div class="icon">
        <i class="ion ion-clipboard"></i>
        </div>
        <a href="{{ route('admin.category.index') }}" class="small-box-footer">Mas info <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
<!-- ./col -->
<div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-yellow">
        <div class="inner">
        <h3>{{ $total['cliente']}}</h3>

        <p>Clientes</p>
        </div>
        <div class="icon">
        <i class="ion ion-person-add"></i>
        </div>
        <a href="{{ route('admin.client.index') }}" class="small-box-footer">Mas info <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
<!-- ./col -->
<div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-red">
        <div class="inner">
        <h3>{{ $total['producto']}}</h3>

        <p>Productos</p>
        </div>
        <div class="icon">
        <i class="ion ion-ios-cart"></i>
        </div>
        <a href="{{ route('admin.product.index') }}" class="small-box-footer">Mas info <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
