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
}
