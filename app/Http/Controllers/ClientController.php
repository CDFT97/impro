<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('created_at', 'desc')->paginate(10);
        
        return Inertia::render('Clients/Index', compact('clients'));
    }

    public function destroy(CLient $client)
    {
        try {
            $client->delete();
            return back()->with('success', 'Cliente eliminado exitosamente');
        } catch (\Throwable $th) {
            Log::error($th);
            return back()->with('error', 'El cliente no se puede eliminar ya que tiene ventas asociadas');
        }
    }

    public function store(ClientRequest $request)
    {
        Client::create($request->validated());
        return back()->with("success", "Cliente Registrado con exito!");
    }

    public function update(ClientRequest $request, Client $client)
    {
        $client->fill($request->validated());
        $client->save();
        return back()->with("success", "Cliente Actualizado con exito!");
    }
}
