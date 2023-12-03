<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DolarController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
    // return Inertia::render('Welcome', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);
});

Route::get("/update-dolar-price", [DolarController::class, 'updateDolar'])->name('dolar.update');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware('auth')->group(function () {
    Route::get('/about', fn () => Inertia::render('About'))->name('about');

    Route::get('users', [UserController::class, 'index'])->name('users.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('clients', ClientController::class);
    Route::resource('providers', ProviderController::class);
    Route::resource('purchases', PurchaseController::class);
    Route::get('products/history', [ProductController::class, 'productHistory'])->name('products.history');
    Route::get('products/history/search', function () {
        return redirect()->route('products.history');
    });
    Route::post('products/history/search', [ProductController::class, 'productHistorySearch'])->name('products.history.search');
    Route::resource('products', ProductController::class);
    
    Route::post("/orders/add-image/{order}", [OrderController::class, 'addImage'])->name("orders.add.image");
    Route::delete("/orders/remove-image/{image}", [OrderController::class, 'removeImage'])->name("orders.delete.image");
    Route::post("/orders/add-product/{order}", [OrderController::class, 'addProduct'])->name("orders.add.product");
    Route::post("/orders/remove-product/{order}", [OrderController::class, 'removeProduct'])->name("orders.remove.product");
    Route::get("/orders/history", [OrderController::class, 'history'])->name('orders.history');
    Route::get("/orders/history/search", function () {
        return redirect()->route('orders.history');
    });
    Route::post("/orders/history/search", [OrderController::class, 'historySearch'])->name('orders.history.search');
    Route::resource('orders', OrderController::class);


    Route::get("provider/show-purchases/{provider}", [ProviderController::class, 'showPurchases'])->name("provider.show.purchases");
});

require __DIR__ . '/auth.php';
