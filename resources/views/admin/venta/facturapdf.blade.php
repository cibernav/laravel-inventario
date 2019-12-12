<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Factura</title>
</head>
<body>
        {{-- 695 x 1123 --}}
    <main>
        <hr>
        <table style="width:695px">
            <tbody>
                <tr>
                    <td style="background-color: white;width: 150px">
                        <img src="{{asset('/adminlte/img/logo-negro-bloque.png')}}" width="150px">
                    </td>
                    <td style="background-color: white;width: 170px">
                        <div style="font-size: 8.5px;text-align: right; line-height: 15px">
                            <br>
                            NIT 71.255.336-6
                            <br>
                            Direccion : Calle 4485- 558-656
                        </div>
                    </td>
                    <td style="background-color: white; width:170px">
                        <div style="font-size: 8.5px; text-align: right; line-height:15px">
                            <br>
                            Telefono: 300 786 25 45
                            <br>
                            ventas@inventoirysustema.com
                        </div>
                    </td>
                    <td style="background-color: white;width:205px">
                        <div style="text-align:center; color:red;">
                            <br>
                                FACTURA N. {{ $data->serie .'-'. $data->numero }}
                            <br>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <table style="font-size:10px; padding:5px 10px; width:695px">
            <tbody>
                <tr>
                    <td style="border: 1px solid #666; background-color: white; width:545px">
                        Cliente : {{ $data->client->nombre }}
                    </td>
                    <td style="border: 1px solid #666; background-color: white; width: 150px;text-align: right">
                        Fecha : {{ $data->fechaemision }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="border: 1px solid #666; background-color: white; width:695px">
                        Vendedor : {{ $data->user->name }}
                    </td>
                </tr>

            </tbody>
        </table>
        <br>
        <table style="font-size: 10px; padding:5px 10px; width:695px">
            <thead>
                <tr>
                    <td style="border:1px solid #666; background-color: white; width:395px; text-align:center">Producto</td>
                    <td style="border:1px solid #666; background-color: white; width:90px; text-align:center">Cant.</td>
                    <td style="border:1px solid #666; background-color: white; width:100px; text-align:center">Precio Unit.</td>
                    <td style="border:1px solid #666; background-color: white; width:100px; text-align:center">Importe</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($datadetalle as $item)
                <tr>
                    <td style="border:1px solid #666; background-color: white; width:395px; text-align:center">{{ $item->product }}</td>
                    <td style="border:1px solid #666; background-color: white; width:90px; text-align:center">{{ $item->cantidad }}</td>
                    <td style="border:1px solid #666; background-color: white; width:100px; text-align:center">{{ $item->price }}</td>
                    <td style="border:1px solid #666; background-color: white; width:100px; text-align:center">{{ $item->importe }}</td>
                </tr>
                @endforeach

            </tbody>
        </table>

        <table style="font-size: 10px; padding:5px 10px; width:695px">
            <tbody>
                <tr>
                    <td style="width:400px">

                    </td>
                    <td style="width:90px">

                    </td>
                    <td style="border:1px solid #666; background-color: white; width:100px; text-align:center">
                        Neto :
                    </td>
                    <td style="border:1px solid #666; background-color: white; width:100px; text-align:center">
                        {{ $data->subtotal }}
                    </td>
                </tr>
                <tr>
                    <td style="width:400px">

                    </td>
                    <td style="width:90px">

                    </td>
                    <td style="border:1px solid #666; background-color: white; width:100px; text-align:center">
                        Igv :
                    </td>
                    <td style="border:1px solid #666; background-color: white; width:100px; text-align:center">
                        {{ $data->igv }}
                    </td>
                </tr>
                <tr>
                    <td style="width:400px">

                    </td>
                    <td style="width:90px">

                    </td>
                    <td style="border:1px solid #666; background-color: white; width:100px; text-align:center">
                        Total :
                    </td>
                    <td style="border:1px solid #666; background-color: white; width:100px; text-align:center">
                        {{ $data->total }}
                    </td>
                </tr>
            </tbody>
        </table>
    </main>
</body>
</html>
