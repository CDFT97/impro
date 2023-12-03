<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderUpdateRequest;
use App\Http\Resources\ClientListResource;
use App\Http\Resources\ProductListResource;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::where("status", Order::STATUS["Pending"])->with("client")->get();
        $clients = ClientListResource::collection(Client::all());
        return Inertia::render('Orders/Pending', compact("orders", 'clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
        ], [
            'client_id.exists' => 'Por favor seleccione un cliente',
        ]);

        $order = Order::create(["client_id" => $request->client_id]);

        return redirect()->route("orders.show", $order);
    }

    public function show(Order $order)
    {
        // dd($order);
        $products = ProductListResource::collection(Product::all());
        return Inertia::render('Orders/Show', compact("order", 'products'));
    }

    public function update(OrderUpdateRequest $request, Order $order)
    {
        $order->update($request->validated());
        return back()->with("success", "Se actualizo correctamente la orden");
    }
    public function destroy(Order $order)
    {
        try {
            $order->delete();
            return back()->with("success", "Se elimino correctamente la orden");
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', 'No se puede eliminar ya que la orden tiene productos, debe cancelarla');
        }
    }
}
