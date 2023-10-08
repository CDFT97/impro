<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with('provider')->orderBy('created_at', 'desc')->paginate(10);
        return Inertia::render("Purchases/Index", compact('purchases'));
    }
    public function store(Request $request)
    {
        //
    }
    public function update(Request $request, Purchase $purchase)
    {
        //
    }
    public function destroy(Purchase $purchase)
    {
        //
    }
}
