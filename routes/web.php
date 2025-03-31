<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FoodIngredientController;
use App\Http\Controllers\FoodItemController;
use App\Http\Controllers\FoodTypeController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\OnlineOrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RevenueStatisticsController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/menu', [HomeController::class, 'menu'])->name('menu');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/cart/checkout', [OnlineOrderController::class, 'store'])->name('cart.processCheckout');
    Route::delete('/online_orders/cancel/{id}', action: [OnlineOrderController::class, 'cancel'])->name('online_orders.cancel');
});

Route::middleware(['auth', 'verified', 'role:staff,admin'])->prefix('manage')->group(function () {
    Route::get('/', [DashboardController::class, 'overview'])->name('manage.dashboard');

    Route::resource('orders', OrderController::class);
    Route::put('/orders/update-paid/{order}', [OrderController::class, 'updatePaid'])->name('orders.updatePaid');
    Route::patch('/orders/update-paid/{order}', [OrderController::class, 'updatePaid'])->name('orders.updatePaid');
    Route::post('/orders/{order}/details', [OrderController::class, 'addOrderDetail'])->name('orders.addDetail');
    Route::put('/order-details/{orderDetail}/status', [OrderController::class, 'updateOrderDetailStatus'])
        ->name('order-details.updateStatus');
    Route::patch('/order-details/{orderDetail}/status', [OrderController::class, 'updateOrderDetailStatus'])
        ->name('order-details.updateStatus');
    Route::delete('/order-details/{orderDetail}', [OrderController::class, 'removeOrderDetail'])
        ->name('order-details.destroy');

    Route::get('/onlineorder', [OnlineOrderController::class, 'index'])->name('online_orders.index');
    Route::get('/online-orders/{id}/items', [OnlineOrderController::class, 'getOrderItems']);
    Route::patch('/online-orders/{id}/update-status', [OnlineOrderController::class, 'updateStatus'])
        ->name('online_orders.update_status');
});

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('manage')->group(function () {
    Route::resource('employees', EmployeeController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('tables', TableController::class);
    Route::resource('food-types', FoodTypeController::class);
    Route::resource('food-items', FoodItemController::class);
    Route::resource('ingredients', IngredientController::class);
    Route::resource('food_ingredients', FoodIngredientController::class);

    Route::get('/statistics', [RevenueStatisticsController::class, 'index'])->name('statistics.index');

    // Route::get('/department', [DepartmentController::class, 'index'])->name('department.index');
    // Route::get('/department/create', [DepartmentController::class, 'create'])->name('department.create');
    // Route::post('/department', [DepartmentController::class, 'store'])->name('department.store');
    // Route::get('/department/{id}/edit', [DepartmentController::class, 'edit'])->name('department.edit');
    // Route::put('/department/{id}', [DepartmentController::class, 'update'])->name('department.update');
    // Route::delete('/department/{id}', [DepartmentController::class, 'destroy'])->name('department.destroy');
});

// Route::get('/invoice', [InvoiceController::class, 'index'])->name('invoice.index');
// Route::get('/invoice/create', [InvoiceController::class, 'create'])->name('invoice.create');
// Route::post('/invoice/store', [InvoiceController::class, 'store'])->name('invoice.store');
// Route::delete('/invoice/{id}', [InvoiceController::class, 'destroy'])->name('invoice.destroy');
// Route::get('/invoice/{id}/edit', [InvoiceController::class, 'edit'])->name('invoice.edit');
// Route::put('/invoice/{id}', [InvoiceController::class, 'update'])->name('invoice.update');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
