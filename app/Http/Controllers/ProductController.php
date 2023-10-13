<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
}
