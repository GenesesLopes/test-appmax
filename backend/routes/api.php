<?php

use App\Http\Controllers\EstoqueController;
use App\Http\Controllers\ProdutoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function(){
    Route::apiResource('produto', ProdutoController::class)
    ->parameters(['produto' => 'id'])
    ->whereNumber('id');
    Route::get('estoque',[EstoqueController::class,'index'])->name('estoque.index');
    Route::put('baixar-produtos',[EstoqueController::class,'update'])->name('estoque.baixa');
    Route::post('adicionar-produtos',[EstoqueController::class,'store'])->name('estoque.adicao');
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
