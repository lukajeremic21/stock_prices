<?php

namespace App\Http\Controllers;

use App\Services\PolygonService;
use Illuminate\Http\JsonResponse;
use App\Models\StockMarketPrice;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockPriceControllerPolygon extends Controller
{
    protected $polygonService;

    public function __construct(PolygonService $polygonService)
    {
        $this->polygonService = $polygonService;
    }

    public function getStockPrices(): JsonResponse
    {
        $stockPrices = $this->polygonService->getStockPrices();

        if (is_array($stockPrices)) {
            return response()->json($stockPrices);
        } else {
            return response()->json(['errors' => $stockPrices], 500);
        }
    }

    public function getAllLatestStockPrices()
    {
        $results = $this->polygonService->getAllLatestStockPrices();

        if (isset($results['error'])) {
            return response()->json(['error' => $results['error']], 500);
        }

        return response()->json($results);
    }

    public function getStockPricesBySymbols(Request $request)
    {
        $symbols = $request->input('symbols');
        
        if (empty($symbols) || !is_array($symbols)) {
            return response()->json(['error' => 'Invalid symbols input'], 400);
        }

        $stockPrices = $this->polygonService->getStockPricesBySymbols($symbols);

        return response()->json($stockPrices);
    }


    public function getPriceChange(Request $request)
    {
        $symbols = $request->input('symbols');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if (empty($symbols) || empty($startDate) || empty($endDate)) {
            return response()->json(['error' => 'Invalid input parameters'], 400);
        }

        $priceChanges = $this->polygonService->calculatePriceChange($symbols, $startDate, $endDate);

        return response()->json($priceChanges);
    }


    public function insertStockPrices(): JsonResponse
    {
        $result = $this->polygonService->insertStockPrices();

        if ($result === true) {
            return response()->json(['message' => 'Stock prices inserted successfully']);
        } else {
            return response()->json(['errors' => $result], 500);
        }
    }




public function getStockPricesByDate($symbol, $date)
    {
        // Find the stock ID
        $stock = Stock::where('symbol', $symbol)->first();
    
        if (!$stock) {
            return response()->json(['message' => 'Stock symbol not found'], 404);
        }
    
        $stockPrices = StockMarketPrice::where('symbol', $stock->id)
                                        ->whereDate('date', $date)
                                        ->first();
    
        if (!$stockPrices) {
            return response()->json(['message' => 'No data found for the given stock symbol and date'], 404);
        }
    
        $stockPrices->symbol = $symbol; 
    
        return response()->json($stockPrices);
    }

}
