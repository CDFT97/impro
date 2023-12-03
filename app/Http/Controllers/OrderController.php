<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderProductRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Resources\ClientListResource;
use App\Http\Resources\ProductListResource;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::where("status", Order::STATUS["Pending"])->get();
        $clients = ClientListResource::collection(Client::all());
        return Inertia::render('Orders/Pending', compact("orders", 'clients'));
    }

    public function history()
    {
        $orders = Order::where("status", "!=" , Order::STATUS["Pending"])->orderBy("created_at", "desc")->paginate(10);
        return Inertia::render('Orders/History', compact("orders"));
    }

    public function historySearch(Request $request)
    {
        $request->validate(["ci" => "required|exists:clients,ci"]);

        $orders = Order::where("status", "!=" , Order::STATUS["Pending"])->whereHas("client", function($q) use($request){
            $q->where("ci", $request->ci);
        })->orderBy("created_at", "desc")->paginate(10);

        return Inertia::render('Orders/History', compact("orders"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
        ], [
            'client_id.exists' => 'Por favor seleccione un cliente',
        ]);

        $order = Order::create(["client_id" => $request->client_id, 'description' => ""]);

        return redirect()->route("orders.show", $order);
    }

    public function show(Order $order)
    {
        $products = ProductListResource::collection(Product::all());
        return Inertia::render('Orders/Show', compact("order", 'products'));
    }

    public function update(OrderUpdateRequest $request, Order $order)
    {
        $order->update($request->validated());
        if($request->status == Order::STATUS["Canceled"]) {
            foreach ($order->products as $product) {
                $product->stock_meters += $product->pivot->m * $product->pivot->quantity;
                $product->save();
                // $order->products()->detach($product->id);
            }
        }
        return redirect()->back()->with("success", "Se actualizo correctamente la orden");
    }

    public function addProduct(Order $order, OrderProductRequest $request)
    {
        $product = Product::find($request->product_id);
        if ($product->stock_meters < ($request->m * $request->quantity)) {
            return back()->with("error", "No hay suficientes metros de material");
        }
        try {
            DB::beginTransaction();
            $order->products()->attach($request->product_id, [
                "dollar_price" => $request->dollar_price,
                "unit_price_usd" => $request->p_unit_usd,
                "unit_price_bs" => $request->p_unit_bs,
                "format" => $request->format,
                "quantity" => $request->quantity,
                "m" => $request->m,
                "m2" => $request->m2,
                "total_price_usd" => $request->p_total_usd,
                "total_price_bs" => $request->p_total_bs
            ]);

            $order->update(['amount' => round($order->amount + $request->p_total_usd, 2)]);

            $product->update(["stock_meters" => round($product->stock_meters - ($request->m * $request->quantity), 2)]);
            DB::commit();
            return back()->with("success", "Se agrego correctamente el producto");
        } catch (\Throwable $th) {
            Log::error($th);
            DB::rollBack();
            return redirect()->back()->with('error', 'No se pudo agregar el producto');
        }
    }

    public function removeProduct(Order $order, OrderProductRequest $request)
    {
        try {
            DB::beginTransaction();
            $product = Product::find($request->product_id);
            $order->products()->detach($request->product_id);

            $order->update(['amount' => round($order->amount - $request->p_total_usd, 2)]);

            $product->update(["stock_meters" => round($product->stock_meters + ($request->m * $request->quantity), 2)]);

            DB::commit();
            return back()->with("success", "Se removió correctamente el producto");
        } catch (\Throwable $th) {
            Log::error($th);
            DB::rollBack();
            return redirect()->back()->with('error', 'No se pudo remover el producto');
        }
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
