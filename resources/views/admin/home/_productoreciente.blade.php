<!-- PRODUCT LIST -->
<div class="box box-primary">
<div class="box-header with-border">
    <h3 class="box-title">Productos recien agregados</h3>

    <div class="box-tools pull-right">
    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
    </button>
    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
    </div>
</div>
<!-- /.box-header -->
<div class="box-body">
    <ul class="products-list product-list-in-box">
        @foreach ($topproducts as $item)
        <li class="item">
            <div class="product-img">
            <img src="{{ $item->photo }}" width="40px" alt="Product Image" class="img-thumbnail" >
            </div>
            <div class="product-info">
            <a href="{{route('admin.product.index', $item->id)}}" class="product-title">{{substr($item->descripcion,1, 50)}}
            <span class="label label-warning pull-right">S/.{{ number_format($item->precio_venta,2) }}</span></a>
            </div>
        </li>
        @endforeach
    </ul>
</div>
<!-- /.box-body -->
<div class="box-footer text-center">
    <a href="{{route('admin.product.index')}}" class="uppercase">Ver Productos</a>
</div>
<!-- /.box-footer -->
