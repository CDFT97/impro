<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClientListResource;
use App\Http\Resources\ProductListResource;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
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
        $products = ProductListResource::collection(Product::all());
        return Inertia::render('Orders/Show', compact("order", 'products'));
    }

    public function update(Request $request, Order $order)
    {
        //
    }
    public function destroy(Order $order)
    {
        //
    }
}
