<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\AuthorArticleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// public routes
Route::post("/login",[AuthController::class,'login'])->name('login');
Route::post("/register",[AuthController::class,'register']);

// protected routes
Route::group(['middleware'=>['auth:sanctum']] , function () {
    Route::post("/logout",[AuthController::class,'logout']);
    Route::get("/articles",[ArticleController::class,'index']);
    Route::get("/authors",[AuthorController::class,'index']);
    
    Route::middleware(['IsManager'])->group(function(){
        Route::resource('/articles',ArticleController::class);
        Route::resource('/authors',AuthorController::class);
        Route::get("/article-authors/{article}",[AuthorArticleController::class,'showArticleAuthors']);
        Route::post("/add-article-author",[AuthorArticleController::class,'storeArticleAuthor']);
        Route::post("/remove-article-author",[AuthorArticleController::class,'destroyArticleAuthors']);
    });
});