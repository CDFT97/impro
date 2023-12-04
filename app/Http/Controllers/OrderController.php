<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderProductRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Http\Resources\ClientListResource;
use App\Http\Resources\ProductListResource;
use App\Models\Client;
use App\Models\Image;
use App\Models\Order;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
            if($order->client->type == 1) {
                Log::info("Total bs: " . $request->p_total_bs);
                Log::info("Total usd: " . $request->p_total_usd);
                $discount = (100 - $order->client->discount) / 100;
                $request->p_total_usd = $request->p_total_usd * $discount;
                $request->p_total_bs = $request->p_total_bs * $discount;
                Log::info("descuento: " . $discount);
                Log::info("New price bs: " . $request->p_total_bs);
                Log::info("New price usd: " . $request->p_total_usd);
            }
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
            
            $order->update(['amount' => $order->amount + $request->p_total_usd]);

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

    public function addImage(Request $request, Order $order)
    {
        try {
            $data = $request->validate([
                'image' => 'required|image|max:3048',
                'image_name' => 'required'
            ]);
            
            $picture = $request->file('image');
            $name_picture = $request->image_name;
            $img_url = "img/orders/{$order->id}/";
            $picture->storeAs($img_url,"{$name_picture}.{$picture->extension()}", 'public');

            Image::create([
                'order_id' => $order->id,
                'name' => $name_picture,
                'url' => asset("storage/{$img_url}{$name_picture}.{$picture->extension()}"),
                "extension" => $picture->extension()
            ]);
            return back()->with("success", "Se agrego correctamente la imagen");
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect()->back()->with('error', 'No se pudo agregar la imagen');
        }
    }

    public function removeImage(Image $image)
    {
        try {
            $image->delete();
            \File::delete(public_path()."/storage/img/orders/{$image->order->id}/{$image->name}.{$image->extension}")  ;  
            return back()->with("success", "Se elimino correctamente la imagen");
        } catch (\Throwable $th) {
            Log::error($th);
            return back()->with('error', 'No se pudo remover la imagen');
        }
    }
}
