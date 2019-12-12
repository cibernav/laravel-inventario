<?php

namespace App\Http\Controllers\Admin;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class ProductController extends Controller
{
    public function __construct(){
        $this->middleware(['roles:Administrador,Especial'])->except(['destroy']);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $data = new Product;
        $category = Category::all();

        return view('admin.producto.index')->with(compact('data', 'category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

        $validator = Validator::make($request->input(), Product::$rules, Product::$messages);
        if ($validator->fails()){
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 422);
        }


        $producto = new Product();
        $producto->codigo = $request->codigo;
        $producto->descripcion = $request->descripcion;
        $producto->categoria_id = $request->categoria_id;
        $producto->stock = $request->stock;
        $producto->precio_compra = $request->precio_compra;
        $producto->precio_venta = $request->precio_venta;
        $producto->ventas = 0;
        $producto->fecha = Carbon::now();

        //if ($request->hasFile('photo')){
          //   $producto->imagen = $request->file('photo')->store('public/producto');
        //}

        $photo = $request->file('photo');
        if($photo){
            $namefile = 'producto/'.time() . $photo->getClientOriginalName();
            $result = Storage::disk('images')->put($namefile, \File::get($photo));
            $producto->imagen = Storage::disk('images')->url($namefile);
        }

        $producto->save();

        return response()->json([
            'error' => false,
            'message' => 'El producto se guardo con exito.'
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

        $producto = Product::find($id);

        $producto->imagen = $producto->photo;

        return response()->json([
            'error' => false,
            'data' => $producto
        ], 200);
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
        $validator = Validator::make($request->input(), Product::rulesUpdate($id), Product::$messages);
        if ($validator->fails()){
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 422);
        }


        $producto = Product::find($id);
        $producto->codigo = $request->codigo;
        $producto->descripcion = $request->descripcion;
        $producto->categoria_id = $request->categoria_id;
        $producto->stock = $request->stock;
        $producto->precio_compra = $request->precio_compra;
        $producto->precio_venta = $request->precio_venta;


        $photo = $request->file('imagen');
        if($photo){

            if($producto->imagen){
                //eliminar
                $photoPath = str_replace('storage', 'public', $producto->imagen);
                Storage::delete($photoPath);
            }

            $namefile = 'producto/'.time() . $photo->getClientOriginalName();
            $result = Storage::disk('images')->put($namefile, \File::get($photo));
            $producto->imagen = Storage::disk('images')->url($namefile);
        }

        $producto->save();

        return response()->json([
            'error' => false,
            'message' => 'El producto se guardo con exito.'
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

        $producto = Product::find($id);
        $this->authorize('delete', $producto);
        $producto->delete();

        if($producto->imagen){
            $photoPath = str_replace('storage', 'public', $producto->imagen);
            Storage::delete($photoPath);
        }


        return response()->json([
            'error' => false,
            'message' => 'El producto se elimino con exito',
            'redirect'  => '',
        ], 200);



    }

    public function getCodigo($id){

        $codigo = Product::where('categoria_id', '=', $id)->max('codigo');
        if(!$codigo){
            $category = Category::findOrFail($id);
            $codigo = $category->id . '01';
        }


        return response()->json([
            'error' => false,
            'data' => (intval($codigo) + 1)
        ], 200);

    }

    public function listar(Request $request){
        $data = Product::
        join('categories', 'categories.id', '=', 'products.categoria_id')
        ->select(
            'products.id',
            'codigo',
            'descripcion',
            'imagen',
            'stock',
            'precio_compra',
            'precio_venta',
            'ventas',
            'fecha',
            'categories.name as categoria'
        );

        // return response()->json([
        //     "draw" => intval( $request['draw'] ),
        //     'recordsTotal' => intval($data->count()),
        //     "recordsFiltered" => intval( $data->count() ),
        //     'data' => $data
        // ], 200);

        return Datatables::of($data)
            //->editColumn('stock', '<button class="btn btn-danger btn-xs">{{ $stock }}</button>')//Blade
            ->editColumn('stock', function($data){
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
            ->editColumn('precio_compra',' S/. {{ number_format($precio_compra,2) }}')
            ->editColumn('precio_venta',' S/. {{ number_format($precio_venta,2) }}')
            ->editColumn('fecha', function($data){
                return Carbon::parse($data->fecha)->format("d/m/y H:m");
            })
            ->rawColumns(['stock'])
            ->make(true);
    }
}
