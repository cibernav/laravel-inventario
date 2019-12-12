<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use App\Client;

class ClientController extends Controller
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
        $client = new Client;
        //dd($client);
        return view('admin.cliente.index')->with(compact('client'));
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


        $validator = Validator::make($request->input(), Client::$rules);
        if ($validator->fails()){
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 422);
        }

        $cliente = new  Client;
        $cliente->nombre = $request->nombre;
        $cliente->documento = $request->documento;
        $cliente->email = $request->email;
        $cliente->telefono = $request->telefono;
        $cliente->direccion = $request->direccion;
        $cliente->fecha_nacimiento = Carbon::parse($request->fecha_nacimiento);
        $cliente->fecha = Carbon::now();
        $cliente->save();

        return response()->json([
            'error' => false,
            'message' => 'El cliente se guardo con exito',
            'redirect' => ''
        ]);

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

        $cliente = Client::findOrFail($id);
        $cliente->fecha_nacimiento = Carbon::parse($cliente->fecha)->format("d/m/Y");
        return response()->json([
            'error' => false,
            'data' => $cliente
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
        $validator = Validator::make($request->input(), Client::rulesUpdate($id));
        if ($validator->fails()){
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 422);
        }

        $cliente = Client::findOrFail($id);
        $cliente->nombre = $request->nombre;
        $cliente->documento = $request->documento;
        $cliente->email = $request->email;
        $cliente->telefono = $request->telefono;
        $cliente->direccion = $request->direccion;
        $cliente->fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $request->fecha_nacimiento);
        $cliente->save();


        return response()->json([
            'error' => false,
            'message' => 'El cliente se actualizo con exito',
            'redirect' => ''
        ]);

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


        $client = Client::find($id);

        $this->authorize('delete', $client);

        $client->delete();

        return response()->json([
            'error' => false,
            'message' => 'El cliente se elimino con exito',
            'redirect' => ''
        ]);
    }

    public function listar(){


        $data = Client::select(['id', 'nombre', 'documento', 'email', 'telefono', 'direccion', 'fecha_nacimiento', 'compras', 'ultima_compra', 'fecha']);

        return Datatables()->of($data)
        ->editColumn('fecha_nacimiento', function($data){
            return Carbon::parse($data->fecha_nacimiento)->format("d/m/Y");
        })
        ->editColumn('fecha', function($data){
            return Carbon::parse($data->fecha)->format("d/m/Y");
        })
        ->editColumn('ultima_compra', function($data){
            return $data->ultima_compra ? Carbon::parse($data->ultima_compra)->format("d/m/Y H:i:s") : '';
        })
        ->make(true);

    }
}
