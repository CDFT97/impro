<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProviderResource;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with('provider')->orderBy('created_at', 'desc')->paginate(10);
        $providers = ProviderResource::collection(Provider::all());
        $products = ProductListResource::collection(Product::all());

        return Inertia::render("Purchases/Index", compact('purchases', 'providers', 'products'));
    }
    public function store(PurchaseRequest $request)
    {
        $purchase = Purchase::create($request->validated());
        
        $product = Product::find($purchase->product_id);
        $product->stock_meters = $product->stock_meters + $purchase->quantity_meters;
        $product->save();

        return back()->with('success', 'Compra registrada exitosamente');
    }
    public function update(PurchaseRequest $request, Purchase $purchase)
    {
        $product = Product::find($purchase->product_id);
        $product->stock_meters -= $purchase->quantity_meters;
        $product->save();
        $purchase->fill($request->validated());
        $purchase->save();

        $product->stock_meters += $purchase->quantity_meters;
        $product->save();
        return back()->with("success", "Compra Actualizado con exito!");
    }
    public function destroy(Purchase $purchase)
    {
        try {
            $purchase->delete();
            return back()->with('success', 'Compra eliminada exitosamente');
        } catch (\Throwable $th) {
            Log::error($th);
            return back()->with('error', 'Ha ocurrido un error al eliminar la compra');
        }
    }
}
