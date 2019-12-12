<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DocumentoCabecera;
use App\Category;
use App\Client;
use App\Product;
class HomeController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(){

        $venta = Documentocabecera::sum('total');
        $categoria = Category::count();
        $cliente = Client::count();
        $producto = Product::count();

        $result = [
                'venta' => $venta,
                'categoria' => $categoria,
                'cliente' => $cliente,
                'producto' => $producto
            ];

        $productos = ReporteController::dataProducto();

        $topproductos = Product::orderby('created_at', 'desc')->take(10)->get();

        return view('admin.home.dashboard')->with([
            'total' => $result,
            'products' => $productos,
            'topproducts' => $topproductos
        ]);
    }
}
