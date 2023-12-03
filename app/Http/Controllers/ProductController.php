<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductOrderQtyResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use stdClass;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy("updated_at", "desc")->paginate(10);
        return Inertia::render("Products/Index", compact("products"));
    }
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return back()->with('success', 'Producto eliminado exitosamente');
        } catch (\Throwable $th) {
            Log::error($th);
            return back()->with('error', 'El producto no se puede eliminar ya que tiene ventas asociadas');
        }
    }
    public function store(ProductRequest $request)
    {
        Product::create($request->validated());
        return back()->with("success", "Proveedor Registrado con exito!");
    }
    public function update(ProductRequest $request, Product $product)
    {
        $product->fill($request->validated());
        $product->save();
        return back()->with("success", "Producto Actualizado con exito!");
    }

    public function productHistory()
    {
        $products = ProductListResource::collection(Product::all());
        return Inertia::render('Products/History', compact('products'));
    }

    public function productHistorySearch(Request $request)
    {
        $product = Product::with("orders")->whereId($request->product_id)->first();
        $products = ProductListResource::collection(Product::all());
        $orders_items = Order::where("status", Order::STATUS["Completed"])
                        ->whereHas("products", function($q) use($request) {
                            $q->where("order_product.product_id", $request->product_id);
                        })
                        ->whereDate("created_at",">=" ,$request->from)
                        ->whereDate("created_at","<=" ,$request->to)
                        ->get();

        $orders = [];
        $total_material = 0;
        foreach ($orders_items as $order) {
            $quantity = $order->products()->where("product_id", $request->product_id)->first()->pivot->quantity;
            $m = $order->products()->where("product_id", $request->product_id)->first()->pivot->m;

            $item = new stdClass();
            $item->id = $order->id;
            $item->amount = $order->amount;
            $item->created_at = $order->created_at;
            $item->description = $order->description;
            $item->qty_material = $quantity * $m;
            $total_material += $item->qty_material;
            $orders[] = $item;
        }
        return Inertia::render('Products/History', compact('products', "product", "orders", "total_material"));
    }
}
