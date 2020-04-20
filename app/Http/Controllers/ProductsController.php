<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Auth;

class ProductsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('products.index');
    }

    public function selections(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $products = Product::all()->toArray();
            return response()->json($products);
        }
        return response(null, 404);
    }
}
