<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Category;
use App\Repositories\CategoryRepo;
use Log;

class CategoryController extends Controller
{
    protected $categoryrepo = null;
    public function __construct(CategoryRepo $CategoryRepo){
        $this->categoryrepo = $CategoryRepo;
        $this->middleware(['roles:Administrador,Especial']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = Category::All();
        Log::info('mensaje de prueba');
        return view('admin.categoria.index')->with(compact('data'));
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

        $validator =  Validator::make($request->input(), Category::$rules);
        if($validator->fails()){
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 422);
        }
        Log::info('se guarda el item');

        $category =  $this->categoryrepo->store($request);

        return response()->json([
            'error' => false,
            'message' => 'La categoria se guardo con exito',
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

        $category = Category::find($id);

        return response()->json([
            'data' => $category
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


        $validator =  Validator::make($request->input(), Category::rulesUpdate($id));
        if($validator->fails()){
            return response()->json([
                'error' => true,
                'message' => $validator->errors()
            ], 422);
        }

        $this->categoryrepo->update($request, $id);


        return response()->json([
            'error' => false,
            'message' => 'La categoria se actualizo con exito',
            'redirect' => ''
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
        $this->authorize('delete', new Category);

        $this->categoryrepo->destroy($id);

        return response()->json([
            'error' => false,
            'message' => 'La categoria se elimino con exito',
            'redirect' => ''
        ], 200);

    }
}
