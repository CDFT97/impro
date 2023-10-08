<?php

namespace App\Http\Controllers;

use App\Models\Dolar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DolarController extends Controller
{
    public function getDolarValue()
    {
        $dolar = Dolar::first();
        return response()->json(["price" => $dolar->amount]);
    }

    public function updateDolar()
    {
        $response = Http::get('https://pydolarvenezuela-api.vercel.app/api/v1/dollar/bcv');
        if($response->successful()) {
            $data = $response->object();
            $dolar = Dolar::first()->update(['amount' => floatval(substr($data->monitors->usd->price,0, 5))]);
            return back()->with("success", "Valor del Dolar actualizado!");
        } 
        return back()->with("error", "Error al actualizar el valor del dolar");
    }
}
