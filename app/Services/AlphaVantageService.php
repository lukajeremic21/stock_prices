<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class AlphaVantageService
{
    protected $client;
    protected $apiKey;
    protected $stocks = ['AAPL', 'MSFT', 'GOOGL', 'AMZN', 'FB']; // Your chosen stocks

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('ALPHA_VANTAGE_API_KEY');
    }
    
    public function getStockPrices()
    {
        $stockPrices = [];
    
        foreach ($this->stocks as $symbol) {
            try {
                $response = $this->client->get('https://www.alphavantage.co/query', [
                    'query' => [
                        'function' => 'GLOBAL_QUOTE',
                        'symbol' => $symbol,
                        'apikey' => $this->apiKey
                    ]
                ]);
    
                // Check for rate limiting
                if ($response->getStatusCode() == 429) {
                    throw new \Exception('API rate limit exceeded.');
                }
    
                $data = json_decode($response->getBody(), true);
    
                if (isset($data['Global Quote'])) {
                    $stockPrices[$symbol] = $data['Global Quote'];
                }
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                // Connection issues
                Log::error('Error connecting to Alpha Vantage API: ' . $e->getMessage());
                // You can handle the connection issue here, for example, by retrying the request after some time
            } catch (\Exception $e) {
                // Other errors
                Log::error('Error fetching stock price for ' . $symbol . ': ' . $e->getMessage());
            }
        }
    
        return $stockPrices;
    }
    

}
