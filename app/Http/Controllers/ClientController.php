<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;
use DB;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::paginate(10);
        
        return Inertia::render('Clients/Index', compact('clients'));
    }
}
