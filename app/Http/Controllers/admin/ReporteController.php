<?php

namespace App\Http\Controllers\Admin;

use App\DocumentoCabecera;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use View;
use Response;
use DB;
use App\Product;
class ReporteController extends Controller
{
    public function __construct(){
        $this->middleware(['roles:Administrador']);
    }

    public function index(Request $request){

        //productos mas vendidos
        $data = DB::table('products')
        ->orderby('ventas', 'desc')
        ->take(10)
        ->select([DB::raw('SUBSTR(descripcion, 1,35) producto'), 'ventas', DB::raw(" '' color")])
        ->get();
        $colors = ['red','blue', 'orange', 'green', 'yellow', 'aqua', 'light-blue', 'gray', 'black', 'bronze'];
        $total = DB::table('products')->orderby('ventas', 'desc')->take(10)->get()->sum('ventas');
        for ($i=0; $i < $data->count(); $i++) {
            $data[$i]->color = $colors[$i];
            $data[$i]->porcentaje = ceil(($data[$i]->ventas *100 ) / $total);
        }

        //mejores vendedores

        $vendedor = DB::table('documentocabecera as dc')
        ->join('users as u', 'u.id', '=', 'dc.id_user')
        ->groupby('dc.id_user')
        ->select([DB::raw('sum(dc.total) total'), DB::raw('u.name nombre')])
        ->take(10)
        ->get();

        //mejores compradores
        $comprador = DB::table('documentocabecera as dc')
        ->join('clients as c', 'c.id', '=', 'dc.id_cliente')
        ->groupby('dc.id_cliente')
        ->select([DB::raw('sum(dc.total) total'), DB::raw('c.nombre nombre')])
        ->take(10)
        ->get();

        //result
        return view('admin.reporte.venta.ventas')->with([
            'products' => $data,
            'vendedor' => $vendedor,
            'comprador' => $comprador
        ]);
    }

    public function getChartVenta(Request $request){
        $flag = false;
        if ($request->fechaini){
            $data = DocumentoCabecera::
            whereBetween('fechaemision', [$request->fechaini . ' 00:00:00', $request->fechafin. ' 23:59:59'])
            ->groupBy(\DB::raw('LEFT(fechaemision, 7)'))
            ->get([
                \DB::raw('LEFT(fechaemision, 7) y'),
                \DB::raw('SUM(total) total')
            ]);
        }
        else{
            $data = DocumentoCabecera::
            groupBy(\DB::raw('LEFT(fechaemision, 7)'))
            ->get([
                \DB::raw('LEFT(fechaemision, 7) y'),
                \DB::raw('SUM(total) total')
            ]);
        }


        //return $data;
        if($data->count() == 0)
          $data->push(['y' => substr(Carbon::now()->toDateString(), 0, 7), 'total' => 0 ]);

        return response()->json([
            'data' =>$data
        ], 200);

    }

    public function getChartProducto(){

        $data = dataProducto();

        return response()->json([
            'data' =>$data
        ], 200);
    }

    public static function dataProducto(){
        $data = DB::table('products')
        ->orderby('ventas', 'desc')
        ->take(10)
        ->select([DB::raw('SUBSTR(descripcion, 1,40) producto'), 'ventas', DB::raw(" '' color")])
        ->get();

        $colors = ['red','blue', 'orange', 'green', 'yellow', 'aqua', 'light-blue', 'gray', 'black', 'bronze'];

        $total = DB::table('products')->orderby('ventas', 'desc')->take(10)->get()->sum('ventas');

        //dd($total);
        for ($i=0; $i < $data->count(); $i++) {
            $data[$i]->color = $colors[$i];
            $data[$i]->porcentaje = ceil(($data[$i]->ventas *100 ) / $total);
        }

        return $data;
    }

    public function getDownloadExcel(Request $request){


        $data = DocumentoCabecera::
        from('documentocabecera as c')
        ->join('clients', 'clients.id', '=', 'c.id_cliente')
        ->join('users', 'users.id' , '=', 'c.id_user')
        //->whereBetween('c.fechaemision', [$request->fechainicio . ' 00:00:00', $request->fechafin. ' 23:59:59'])
        ->select(
            'c.id',
            'serie',
            'numero',
            'clients.nombre as cliente',
            'users.name as vendedor',
            'c.fechaemision',
            'c.id_metodopago',
            'subtotal',
            'igv',
            'total'
        )->get();

        $view = View::make('admin.reporte.venta.excel', [
            'ventas' => $data
        ]);

        $html = $view->render();

        //dd($html);

        $filename = rand();
        $headers = array(
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename=' . $filename .'.xls',
        );

        return Response::make($html, 200, $headers);

    }

    public function getDownloadCsv(Request $request){


        $data = DocumentoCabecera::
        from('documentocabecera as c')
        ->join('clients', 'clients.id', '=', 'c.id_cliente')
        ->join('users', 'users.id' , '=', 'c.id_user')
        ->whereBetween('c.fechaemision', [$request->fechaini . ' 00:00:00', $request->fechafin. ' 23:59:59'])
        ->select(
            'c.id',
            'serie',
            'numero',
            'clients.nombre as cliente',
            'users.name as vendedor',
            'c.fechaemision',
            'c.id_metodopago',
            'subtotal',
            'igv',
            'total'
        )->get();

        $filecsv = implode(',', array('SERIE', 'NUMERO', 'CLIENTE', 'VENDEDOR', 'FECHA', 'METODO PAGO', 'NETO', 'IMPUESTO', 'TOTAL'));

        foreach ($data as $key => $value) {
            $filecsv .= PHP_EOL . implode(',', [
                    $value->serie,
                    $value->numero,
                    $value->cliente,
                    $value->vendedor,
                    Carbon::parse($value->fechaemision)->format('d-m-Y'),
                    $value->metodopago,
                    number_format($value->subtotal,2),
                    number_format($value->igv, 2),
                    number_format($value->total, 2)
                ]);
        }

        //dd(rtrim($filecsv, "\n"));

        $filename = rand();
        $headers = array(
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=' . $filename .'.csv',
            'Expires' => '0'
        );

        return Response::make($filecsv, 200, $headers);

    }
}
