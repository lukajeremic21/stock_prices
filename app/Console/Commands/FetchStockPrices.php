<?php 

namespace App\Console\Commands;

use Illuminate\Console\Command;
// use App\Http\Controllers\StockPriceControllerPolygon;
use App\Services\PolygonService;

class FetchStockPrices extends Command
{
    protected $signature = 'fetchStockPrices';
    protected $description = 'Fetch stock prices from Alpha Vantage API';

    protected $polygonService;

    public function __construct(PolygonService $polygonService)
    {
        parent::__construct();
        $this->polygonService = $polygonService;
    }

    public function handle()
    {
        $this->polygonService->insertStockPrices();
    }
}
