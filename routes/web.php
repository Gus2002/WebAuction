<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AuctionController;
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

Route::get('/', [AuctionController::class, 'index']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::post('/logout', [LogoutController::class, 'store'])->name('logout');

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store']);

Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/auctions', [AuctionController::class, 'index'])->name('auctions');

Route::get('/new-auction', [AuctionController::class, 'create'])->name('new-auction');
Route::post('/new-auction', [AuctionController::class, 'store']);

Route::get('/show/{id}', [AuctionController::class, 'show']);
Route::post('/show/{id}', [AuctionController::class, 'storebid']);

Route::get('/create-transaction/{id}', [AuctionController::class, 'createtransaction']);
Route::post('/create-transaction/{id}', [AuctionController::class, 'storetransaction']);

Route::delete('/dashboard/{auction}', [AuctionController::class, 'destroy'])->name('destroy-auction');

Route::get('/update/{id}', [AuctionController::class, 'showupdate']);
Route::post('/update/{id}', [AuctionController::class, 'update']);
