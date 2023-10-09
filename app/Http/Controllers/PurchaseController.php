<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Http\Resources\ProviderResource;
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
        return Inertia::render("Purchases/Index", compact('purchases', 'providers'));
    }
    public function store(PurchaseRequest $request)
    {
        Purchase::create($request->validated());
        return back()->with('success', 'Compra registrada exitosamente');
    }
    public function update(PurchaseRequest $request, Purchase $purchase)
    {
        $purchase->fill($request->validated());
        $purchase->save();
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
