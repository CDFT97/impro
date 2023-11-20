<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    private $productRepository;
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    public function index()
    {
        if (!Auth::User()->isAdmin()) {
            return Inertia::render('Dashboard/User');
        } else {
            $products = $this->productRepository->getLowMettersStocks();
            return Inertia::render('Dashboard/Admin', compact("products"));
        }
    }
}
