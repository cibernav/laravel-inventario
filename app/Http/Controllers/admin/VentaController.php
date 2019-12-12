<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use App\Product;
use App\Category;
use App\DocumentoCabecera;
use App\DocumentoDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use PDF;

class VentaController extends Controller
{
    public function __construct(){
        $this->middleware(['roles:Administrador,Vendedor']);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = new DocumentoCabecera;

        //dd($data);

        return view('admin.venta.index')->with(compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $dataclient = Client::select(['id', 'nombre'])->orderby('nombre')->get();
        $datacategory = Category::select()->orderby('name')->get();
        return view('admin.venta.create')->with(compact('dataclient', 'datacategory'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->input(), DocumentoCabecera::$rules);
        if ($validator->fails()){
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 422);
        }

        $items = collect(json_decode($request->items));
        if ($items->count() == 0){
            return response()->json([
                'error' => true,
                'message' => 'Debe ingresar mas articulos.',
                'redirect' => ''
            ], 200);
        }

        $cliente = Client::find((int)$request->id_cliente);

        $documento = new DocumentoCabecera;
        $documento->id_tipodocumento = 1;
        $documento->serie = $request->serie;
        $documento->numero = $request->numero;
        $documento->id_cliente = $request->id_cliente;
        $documento->nrodocumento = $cliente->documento;
        $documento->fechaemision = Carbon::now();
        $documento->id_metodopago = $request->id_metodopago;
        $documento->detallepago = $request->detallepago;
        $documento->subtotal = $request->subtotal;
        $documento->igv = $request->impuesto;
        $documento->total = $request->totalventa;
        $documento->id_user = 1;

        $documento->save();

        $lista = [];
        $totalcompra = 0;
        foreach ($items as $key => $value) {
            $item = new DocumentoDetalle;
            $item->id_documentocabecera = $documento->id;
            $item->id_producto = $value->idproduct;
            $item->cantidad = $value->cantidad;
            $item->precio_unitario = $value->price;
            $item->importe = $item->cantidad * $item->precio_unitario;
            $lista[] = $item;

            $producto = Product::find($value->idproduct);
            $producto->ventas = $producto->ventas + $item->cantidad;
            $producto->stock = $producto->stock - $item->cantidad;
            $producto->save();

            $totalcompra += $value->cantidad;
        }

        $documento->items()->saveMany($lista);

        //update cliente compras
        $cliente->compras = $cliente->compras + $totalcompra;
        $cliente->ultima_compra = $documento->fechaemision;
        $cliente->save();



        return response()->json([
            'error' => false,
            'message' => 'La venta se registro con exito.',
            'redirect' => ''
        ], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $dataventa = DocumentoCabecera::find($id);

        $this->authorize('update', $dataventa);

        //$datadetalle = DocumentoDetalle::find();
        $dataclient = Client::select(['id', 'nombre'])->orderby('nombre')->get();
        $datacategory = Category::select()->orderby('name')->get();
        return view('admin.venta.edit')->with(compact('dataclient', 'datacategory', 'dataventa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $this->authorize('update', DocumentoCabecera::class);

        $items = collect(json_decode($request->items));
        if ($items->count() == 0){
            return response()->json([
                'error' => true,
                'message' => 'Debe ingresar mas articulos.',
                'redirect' => ''
            ], 200);
        }


        $cliente = Client::find($request->id_cliente);

        $documento = DocumentoCabecera::find($request->id);
        $documento->id_tipodocumento = 1;
        $documento->serie = $request->serie;
        $documento->numero = $request->numero;
        $documento->id_cliente = $request->id_cliente;
        //$documento->nrodocumento = $cliente->documento;
        $documento->fechaemision = Carbon::now();
        $documento->id_metodopago = $request->id_metodopago;
        $documento->detallepago = $request->detallepago;
        $documento->subtotal = $request->subtotal;
        $documento->igv = $request->impuesto;
        $documento->total = $request->totalventa;
        //$documento->id_user = $user->id;

        $documento->save();

        $lista = [];
        $totalcompra = 0;
        //1= Nuevo - 2 = Editar - 3 = Eliminar

        $itemsPrev = DocumentoDetalle::where('id_documentocabecera', '=', $id)->get();

        foreach ($items as $key => $value) {
            if ($value->tipo == 1){
                $item = new DocumentoDetalle;
                $item->id_documentocabecera = $documento->id;
                $item->id_producto = $value->idproduct;
                $item->cantidad = $value->cantidad;
                $item->precio_unitario = $value->price;
                $item->importe = $item->cantidad * $item->precio_unitario;
                $lista[] = $item;

                $producto = Product::find($value->idproduct);
                $producto->ventas = $producto->ventas + $item->cantidad;
                $producto->stock = $producto->stock - $item->cantidad;
                $producto->save();
            }
            else if ($value->tipo == 2){
                $itemPrev = $itemsPrev->firstWhere('id', $value->id);

                $producto = Product::find($value->idproduct);

                $item = DocumentoDetalle::find($value->id);
                $item->id_documentocabecera = $documento->id;
                $item->id_producto = $value->idproduct;
                $item->cantidad = $value->cantidad;
                $item->precio_unitario = $value->price;
                $item->importe = $item->cantidad * $item->precio_unitario;
                $lista[] = $item;

                $producto->ventas = $producto->ventas - $itemPrev->cantidad + $item->cantidad;
                $producto->stock = $producto->stock + $itemPrev->cantidad - $item->cantidad;
                $producto->save();
            }
            else if ($value->tipo == 3){
                $itemPrev = $itemsPrev->firstWhere('id', $value->id);

                $item = DocumentoDetalle::find($value->id);
                $item->delete();
            }

            $totalcompra += $value->cantidad;
        }


        $documento->items()->saveMany($lista);

        $cliente->compras = $cliente->compras + $totalcompra;
        $cliente->save();

        return response()->json([
            'error' => false,
            'message' => 'La venta se actualizo con exito.',
            'redirect' => route('admin.venta.index')
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $this->authorize('delete', DocumentoCabecera::class);

        $items = DocumentoDetalle::where('id_documentocabecera', '=', $id)->get();
        $documentoCabecera = DocumentoCabecera::find($id);
        $totalcompra = $items->count();

        $documets = DocumentoCabecera::where('id', '<>' , $id)
        ->where('id_cliente', '=', $documentoCabecera->id_cliente)
        -> orderByDesc('id')->take(1)->get();

        foreach ($items as $key => $value) {

            $producto = Product::find($value->id_producto);
            $producto->ventas = $producto->ventas - $value->cantidad;
            $producto->stock = $producto->stock + $value->cantidad;
            $producto->save();

            DocumentoDetalle::destroy($value->id);
        }

        $documentoCabecera->delete();




        $cliente = Client::find($documentoCabecera->id_cliente);
        $cliente->compras = $cliente->compras - $totalcompra;
        if (Carbon::parse($documentoCabecera->fechaemision)->greaterThanOrEqualTo($cliente->ultima_compra)){
            if($documets->count() > 0)
                $cliente->ultima_compra = $documets[0]->fechaemision;
            else {
                $cliente->ultima_compra = null;
            }
        }
        else if ($documets->count() == 0)
            $cliente->ultima_compra = null;

        $cliente->save();

        return response()->json([
            'error' => false,
            'message' => 'La venta se elimino con exito.',
            'redirect' => ''
        ], 200);
    }
    /**
    * Gnerar nueva sere desde venta nueva
    */
    public function numerodocumento(){

        $row = DocumentoCabecera::where('id_tipodocumento', '=', '1')->orderbyDesc('fechaemision')->select('numero')->first();

        if($row == null){
            $numero = 100;
        }else {
            $numero = $row->numero + 1;
        }


       return response()->json([
        'data' => $numero
       ], 200);

    }

    /**
    * Listar productos a agregar en venta nueva
     */
    public function listarProduct(Request $request){
        $data = Product::
        join('categories', 'categories.id', '=', 'products.categoria_id')
        ->where('products.categoria_id' , '=' , $request->idcategory )
        ->select(
            'products.id',
            'codigo',
            'descripcion',
            'imagen',
            'stock',
            'precio_venta'
        );

        //dd($data->get());

        return Datatables::of($data)
            //->editColumn('stock', '<button class="btn btn-danger btn-xs">{{ $stock }}</button>')//Blade
            ->editColumn('descripcion', '{{ substr($descripcion,0, 40) }}')
            ->addColumn('stockbtn', function($data){
                if(intval($data->stock) <= 10)
                    return '<button class="btn btn-danger btn-xs">' .$data->stock.' </button>';
                else if (intval($data->stock) > 11 && intval($data->stock) < 15)
                    return '<button class="btn btn-success btn-xs">' .$data->stock.' </button>';
                else {
                    return '<button class="btn btn-warning btn-xs">' .$data->stock.' </button>';
                }
            })
            ->editColumn('imagen', function($data){
                if($data->imagen == null)
                    return '/storage/anonymous_prod.png';
                else
                    return $data->imagen;
            })
            ->rawColumns(['stockbtn'])
            ->toJson();
    }
    /**
    * Buscar producto desde venta nueva
     */
    public function searchProduct(Request $request){

        //dd($request->input('query'));
        $product = Product::
        where('descripcion' , 'like', $request->input('query') . '%')
        ->select(['id', \DB::raw('SUBSTRING(descripcion, 1, 80) descripcion'), \DB::raw('precio_venta precio'), 'stock'])->take(20)->get();

        return response()->json([
            'data' => $product
        ]);
    }
    /**
    * Listar detalle en venta editar
    */
    public function itemDetail($id){

        $datadetalle = DocumentoDetalle::
        from('documentodetalle as d')
        ->join('products as p', 'p.id', '=' , 'd.id_producto')
        ->where('d.id_documentocabecera', '=', $id)
        ->select(
            'd.id',
            'd.id_producto as idproduct',
            'p.descripcion as product',
            'd.precio_unitario as price',
            'd.cantidad',
            'p.stock',
            \DB::raw('4 as tipo')
        )->get();

        return response()->json([
            'data' => $datadetalle
        ]);

    }

    /**
    * Imprimir pdf desde bandeja index
     */
    public function printer($id){

        //

        $data = DocumentoCabecera::with('client')
        ->with('user')
        ->find($id);

        $datadetalle = DocumentoDetalle::
        from('documentodetalle as d')
        ->join('products as p', 'p.id', '=' , 'd.id_producto')
        ->where('d.id_documentocabecera', '=', $id)
        ->select(
            'd.id',
            'd.id_producto as idproduct',
            'p.descripcion as product',
            'd.precio_unitario as price',
            'd.importe',
            'd.cantidad',
            'p.stock'
        )->get();

        $data->fechaemision = Carbon::parse($data->fechaemision)->format('d/m/Y');
        $data->subtotal = '$/.' . $data->subtotal;
        $data->igv = '$/.' . $data->igv;
        $data->total = '$/.' . $data->total;

        //cargar vista con la data
        $pdf = PDF::loadView('admin.venta.facturapdf', [ 'data' => $data, 'datadetalle' => $datadetalle]);

        PDF::setOptions(['dpi' => 96, 'defaultFont' => 'sans-serif']);
        //guardar el pdf en el server
        //$pdf->save(storage_path().'_filename.pdf');

        return $pdf->stream('doc.pdf');
        //return $pdf->download('invoice');
        //return view('admin.venta.facturapdf', compact('data', 'datadetalle'));
    }

    public function listarBandeja(Request $request){

        $data = DocumentoCabecera::
        from('documentocabecera as c')
        ->join('clients', 'clients.id', '=', 'c.id_cliente')
        ->join('users', 'users.id' , '=', 'c.id_user')
        ->whereBetween('c.fechaemision', [$request->fechainicio . ' 00:00:00', $request->fechafin. ' 23:59:59'])
        ->select(
            'c.id',
            'id_tipodocumento',
            'serie',
            'numero',
            'c.id_cliente',
            'clients.nombre as cliente',
            'users.name as vendedor',
            'id_metodopago',
            'c.fechaemision',
            'subtotal',
            'igv',
            'total'
        )->get();

        return Datatables()->of($data)
        ->addColumn('metodopago', function($data){
            return $data->metodopago;
        })
        ->toJson();
    }
}
