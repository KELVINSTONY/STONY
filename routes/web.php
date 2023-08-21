<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\PrescriptionItemController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReceiveController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\GoodsReceivingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// routes/web.php
Route::get('/', [HomeController::class,'login']);
Auth::routes(['register' => false]);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile_index', [ProfileController::class, 'index'])->name('profile-index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [HomeController::class,'index'])->name('dashboard');
    Route::get('/update', [HomeController::class,'updating'])->name('update');
    Route::get('/allocation', [HomeController::class,'allocation'])->name('allocation');
    Route::get('pharmacy/purchases/goods-receiving', 'GoodsReceivingController@index')->name('goods-receiving.index');
    Route::get('/goods-received/create', [GoodsReceivingController::class, 'create'])->name('goods-received.create');
    Route::post('/goods-received/store', [GoodsReceivingController::class, 'store'])->name('goods-received.store');

Route::resource('medicines', MedicineController::class);
Route::resource('patients', PatientController::class);
Route::resource('prescriptions', PrescriptionController::class);
Route::resource('prescription_items', PrescriptionItemController::class);
Route::resource('doctors', DoctorController::class);


Route::resource('sales', SaleController::class);
Route::resource('receive', ReceiveController::class);
Route::get('stock', [StockController::class,'index'])->name('stocking');
Route::get('sale', [StockController::class,'sale'])->name('sale');
Route::post('sale_store', [SaleController::class,'store'])->name('sale-store');
Route::get('return', [SaleController::class,'returnProcess'])->name('return');
Route::post('return_store', [SaleController::class,'processReturn'])->name('return-store');
Route::get('export/medicines', [StockController::class,'exportToExcel'])->name('export.medicines');


Route::resource('roles', RoleController::class);
Route::get('invoice', [InvoiceController::class, 'Invoice']);
});

// Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/permissions', [PermissionController::class,'index'])->name('permissions.index');
    Route::post('/permissions/{user}', [PermissionController::class,'update'])->name('permissions.update');
// });

require __DIR__.'/auth.php';
