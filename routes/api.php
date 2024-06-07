<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Services\AlphaVantageService;
use App\Services\PolygonService;
use App\Http\Controllers\StockPriceControllerPolygon;

Route::post('/getStockPricesBySymbols', [StockPriceControllerPolygon::class, 'getStockPricesBySymbols']);

Route::get('/getStockPricePolygon', [StockPriceControllerPolygon::class, 'getStockPrices']);

Route::get('/getAllLatestStockPrices', [StockPriceControllerPolygon::class, 'getAllLatestStockPrices']);

Route::post('/insertStockPrices', [StockPriceControllerPolygon::class, 'insertStockPrices']);
Route::get('/stock-prices/{symbol}/{date}', [StockPriceControllerPolygon::class, 'getStockPricesByDate']);

Route::post('/getPriceChange', [StockPriceControllerPolygon::class, 'getPriceChange']);



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
