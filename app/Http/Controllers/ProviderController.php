<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProviderController extends Controller
{
    public function index()
    {
        $providers = Provider::orderBy('created_at', 'desc')->paginate(10);
        return Inertia::render('Providers/Index', compact('providers'));
    }

    public function destroy(Provider $provider)
    {
        try {
            $provider->delete();
            return back()->with('success', 'Proveedor eliminado exitosamente');
        } catch (\Throwable $th) {
            Log::error($th);
            return back()->with('error', 'El proveedor no se puede eliminar ya que tiene compras asociadas');
        }
    }
}
