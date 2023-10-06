<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;
use DB;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::paginate(10);
        
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
}
