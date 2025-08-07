<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BoxController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CopyController;
use App\Models\Box;

Route::get('/', function () {
    $boxes = Box::all();
    return view('page_accueil', ['boxes' => $boxes]);
})->name('accueil');

Route::get('/mentions_legales', function(){
    return view('mentions_legales');
})->name('ml');


Route::get('/gestion_users', [UserController::class, 'index'])->name('gestion_users')->middleware('can:manage users'); 
Route::resource('users', UserController::class)->middleware('can:manage users');

Route::get('/gestion_boites', [BoxController::class, 'index'])->name('gestion_boites')->middleware('can:manage books');
Route::get('/gestion_boites/{box}', [BoxController::class, 'show'])->name('gestion_boites.show')->middleware('can:manage books');
Route::get('/boxes/{box}/inventory', [BoxController::class, 'inventory'])->name('boxes.inventory')->middleware('can:manage books');
Route::post('/boxes/{box}/inventory', [BoxController::class, 'saveInventory'])->name('boxes.saveInventory')->middleware('can:manage books');
Route::delete('/copies/{copy}', [CopyController::class, 'destroy'])->name('copies.destroy');

// Route::get('/books/add_isbn', function(){
//     return view('web.books.test');
// })->name("web.books.test");

//évite les conflits avec l'API
Route::resource('books', BookController::class)->names([
    'index' => 'web.books.index',
    'create' => 'web.books.create',
    'store' => 'web.books.store',
    'show' => 'web.books.show',
    'edit' => 'web.books.edit',
    'update' => 'web.books.update',
    'destroy' => 'web.books.destroy',
])->middleware('can:manage books');

Route::post('/books/{book}/copy', [BookController::class, 'createCopy'])->name('books.createCopy');

Route::resource('boxes', BoxController::class)->names([
    'index' => 'web.boxes.index',
    'create' => 'web.boxes.create',
    'store' => 'web.boxes.store',
    'show' => 'web.boxes.show',
    'edit' => 'web.boxes.edit',
    'update' => 'web.boxes.update',
    'destroy' => 'web.boxes.destroy',
])->middleware('can:manage books');


Route::get('/dashboard', function () {
    $totalBoxes = Box::count();
    $checkedBoxes = Box::whereNotNull('last_inventory')->count(); // celles qu'on a déjà fait
    $inventoryProgress = $totalBoxes > 0 ? round(($checkedBoxes / $totalBoxes) * 100, 2) . '%' : '0%';
    return view('dashboard', ['totalBoxes' => $totalBoxes, 'checkedBoxes' => $checkedBoxes, 'inventoryProgress' => $inventoryProgress]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
