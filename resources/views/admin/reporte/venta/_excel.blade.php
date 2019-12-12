<table>
    <thead>
        <tr>
            <td style="font-weight: bold; border:1px solid #eee;">CODIGO</td>
            <td style="font-weight: bold; border:1px solid #eee;">CLIENTE</td>
            <td style="font-weight: bold; border:1px solid #eee;">VENDEDOR</td>
            <td style="font-weight: bold; border:1px solid #eee;">FECHA</td>
            <td style="font-weight: bold; border:1px solid #eee;">METODO PAGO</td>
            <td style="font-weight: bold; border:1px solid #eee;">CANTIDAD</td>
            <td style="font-weight: bold; border:1px solid #eee;">PRODUCTOS</td>
            <td style="font-weight: bold; border:1px solid #eee;">NETO</td>
            <td style="font-weight: bold; border:1px solid #eee;">IMPUESTO</td>
            <td style="font-weight: bold; border:1px solid #eee;">TOTAL</td>

        </tr>
    </thead>
    <tbody>
        @foreach ($ventas as $item)
        <tr>
            <td>{{ $item->serie . '-' . $item->numero }}</td>
            <td>{{ $item->cliente }}</td>
            <td>{{ $item->vendedor }}</td>
            <td>{{ $item->fechaemision }}</td>
            <td>{{ $item->metodopago }}</td>
            <td></td>
            <td></td>
            <td>{{ number_format($item->subtotal, 2) }}</td>
            <td>{{ number_format($item->igv, 2) }}</td>
            <td>{{ number_format($item->total, 2) }}</td>
        </tr>
        @endforeach

    </tbody>
</table>
