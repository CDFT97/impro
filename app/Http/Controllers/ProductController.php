<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
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
    public function store(ProviderRequest $request)
    {
        Provider::create($request->validated());
        return back()->with("success", "Proveedor Registrado con exito!");
    }
    public function update(ProviderRequest $request, Provider $provider)
    {
        $provider->fill($request->validated());
        $provider->save();
        return back()->with("success", "Proveedor Actualizado con exito!");
    }
}
