<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Auth;
use App\Http\Requests\ProductFormRequest;

class ProductsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, Product $product)
    {
        $name = isset($request->name) ? $request->name : null;
        $min_price = isset($request->min_price) && is_numeric($request->min_price) && floatval($request->min_price) >= 0
            ? floatval($request->min_price) : null;
        $max_price = isset($request->max_price) && is_numeric($request->max_price) && floatval($request->max_price) >= 0
            ? floatval($request->max_price) : null;
        $order_by = isset($request->order_by) ? $request->order_by : 0;

        $products = $product->filterByName($name)
            ->filterBetweenPrices($min_price, $max_price)
            ->withOrder($order_by)
            ->paginate(10);
        return view('products.index', compact('products', 'name', 'min_price', 'max_price', 'order_by'));
    }

    public function create(Product $product)
    {
        return view('products.create_or_edit', compact('product'));
    }

    public function store(ProductFormRequest $request, Product $product)
    {
        $product->fill($request->all());
        $product->save();
        return redirect()->to(route('products.index'))->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        return view('products.create_or_edit', compact('product'));
    }

    public function update(ProductFormRequest $request, Product $product)
    {
        $product->update($request->all());
        return redirect()->to(route('products.index'))->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->to(route('products.index'))->with('success', 'Product deleted successfully');
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
