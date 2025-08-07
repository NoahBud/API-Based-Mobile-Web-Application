<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookApiController;
use App\Http\Controllers\BoxApiController;
use App\Http\Controllers\CopyController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\AuthorController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::apiResource('boxes', BoxApiController::class)->except(['store', 'update', 'destroy']);

// permet d'autoriser SEULEMENT les utilisateurs connectés sur téléphone d'ajouter une boîte
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('boxes', [BoxApiController::class, 'store']);
    Route::put('boxes/{box}', [BoxApiController::class, 'update']);
    Route::delete('boxes/{box}', [BoxApiController::class, 'destroy']);
});

Route::apiResource('books', BookApiController::class);
Route::apiResource('copies', CopyController::class);
Route::apiResource('authors', AuthorController::class);
Route::apiResource('editors', EditorController::class);

Route::get('/books/copies/{book}', [BookApiController::class, 'getCopies']);
Route::get('/books/authors/{book}', [BookApiController::class, 'getAuthors']);
Route::get('/books/isbn/{isbn}', [BookApiController::class, 'getByISBN']);
Route::post('/books/{book}/copies', [BookApiController::class, 'createCopy']);

Route::get('/boxes/{box}/copies', [BoxApiController::class, 'getCopies']);

Route::get('/copies/authors/{copy}', [CopyController::class, 'getAuthors']);

Route::put('/copies/{copy}/disponibilite', [CopyController::class, 'setDisponibility']);

// Route::get('/books/isbn/{isbn}', [BookApiController::class, 'fetchByISBN'])->where('isbn', '[0-9Xx\-]+');
