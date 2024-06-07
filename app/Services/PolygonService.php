<?php 

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\StockMarketPrice;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Exception\RequestException;
use App\Models\Stock;

class PolygonService
{
    protected $client;
    protected $apiKey;
    protected $stocks = ['AAPL', 'MSFT', 'GOOGL', 'AMZN', 'NVDA']; 

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('POLYGON_API_KEY');
    }

    
    public function getAllLatestStockPrices()
    {
        $results = [];
        $previousDate = Carbon::now()->subDay()->toDateString();

        try {
            $response = $this->client->get("https://api.polygon.io/v2/aggs/grouped/locale/us/market/stocks/{$previousDate}?adjusted=true", [
                'query' => [
                    'apiKey' => $this->apiKey
                ],
                'timeout' => 10, // Timeout in seconds
                'retry_on_timeout' => true, // Retry on timeout
                'http_errors' => false, // Do not throw exceptions for HTTP errors
            ]);

            // Check if the request was successful (status code 2xx)
            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                $data = json_decode($response->getBody(), true);

                if (isset($data['status']) && $data['status'] == 'OK') {
                    $results = $data;
                } else {
                    return ['error' => 'Invalid response from API'];
                }
            } elseif ($response->getStatusCode() == 429) {
                return ['error' => 'API rate limit exceeded'];
            } else {
                return ['error' => 'Failed to fetch stock prices'];
            }
        } catch (RequestException $e) {
            Log::error('Error connecting to Polygon API: ' . $e->getMessage());
            return ['error' => 'Failed to connect to API'];
        } catch (\Exception $e) {
            Log::error('Error fetching stock prices: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }

        return $results;
    }
    
    public function getStockPrices()
    {
        // Check if stock prices are cached
        $cachedStockPrices = Cache::get('stock_prices');
        if ($cachedStockPrices !== null) {
            return $cachedStockPrices;
        }
    
        $stockPrices = [];
        $previousDate = Carbon::now()->subDay()->toDateString();
        $errors = [];
    
        foreach ($this->stocks as $symbol) {
            try {
                $response = $this->client->get('https://api.polygon.io/v1/open-close/' . $symbol . '/' . $previousDate, [
                    'query' => [
                        'adjusted' => true,
                        'apiKey' => $this->apiKey
                    ],
                    'timeout' => 10, // Timeout in seconds
                    'retry_on_timeout' => true, // Retry on timeout
                    'http_errors' => false, // Do not throw exceptions for HTTP errors
                ]);
    
                // Check if the request was successful (status code 2xx)
                if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                    $data = json_decode($response->getBody(), true);
    
                    if (isset($data['status']) && $data['status'] == 'OK') {
                        $stockPrices[$symbol] = $data;
                    } else {
                        $errors[] = 'Error fetching stock price for ' . $symbol . ': Invalid response';
                    }
                } elseif ($response->getStatusCode() == 429) {
                    $errors[] = 'API rate limit exceeded for symbol ' . $symbol;
                } else {
                    $errors[] = 'Error fetching stock price for ' . $symbol . ': HTTP ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase();
                }
            } catch (RequestException $e) {
                // Connection issues, retry after a delay
                Log::error('Error connecting to Polygon API for symbol ' . $symbol . ': ' . $e->getMessage());
                $errors[] = 'Error connecting to Polygon API for symbol ' . $symbol . ': ' . $e->getMessage();
            } catch (\Exception $e) {
                // Other errors
                Log::error('Error fetching stock price for ' . $symbol . ': ' . $e->getMessage());
                $errors[] = 'Error fetching stock price for ' . $symbol . ': ' . $e->getMessage();
            }
        }
    
        // Cache the fetched stock prices for 1 minute
        Cache::put('stock_prices', $stockPrices, Carbon::now()->addMinutes(1));
    
        return empty($stockPrices) ? $errors : $stockPrices;
    }
    
    

    public function calculatePriceChange(array $symbols, $startDate, $endDate)
    {
        $priceChanges = [];
    
        foreach ($symbols as $symbol) {
            // Fetch the start date price
            $startResponse = $this->client->get('https://api.polygon.io/v1/open-close/' . $symbol . '/' . $startDate, [
                'query' => [
                    'adjusted' => true,
                    'apiKey' => $this->apiKey
                ],
                'timeout' => 10, // Timeout in seconds
                'retry_on_timeout' => true, // Retry on timeout
                'http_errors' => false, // Do not throw exceptions for HTTP errors
            ]);
    
            // Fetch the end date price
            $endResponse = $this->client->get('https://api.polygon.io/v1/open-close/' . $symbol . '/' . $endDate, [
                'query' => [
                    'adjusted' => true,
                    'apiKey' => $this->apiKey
                ],
                'timeout' => 10, // Timeout in seconds
                'retry_on_timeout' => true, // Retry on timeout
                'http_errors' => false, // Do not throw exceptions for HTTP errors
            ]);
    
            $startData = json_decode($startResponse->getBody(), true);
            $endData = json_decode($endResponse->getBody(), true);
    
            if (isset($startData['close']) && isset($endData['close'])) {
                $closeValueStart = $startData['close'];
                $closeValueEnd = $endData['close'];
    
                // Calculate the price change
                $priceChange = ($closeValueEnd - $closeValueStart) / $closeValueStart;
    
                // Format the price change as a percentage
                $priceChanges[$symbol] = $priceChange * 100 . '%';
            } else {
                $priceChanges[$symbol] = 'Error fetching data';
            }
        }
    
        return $priceChanges;
    }
    

    
    
    
    public function getStockPricesBySymbols(array $symbols)
    {
        $results = [];
    
        foreach ($symbols as $symbol) {
            // Cache key for the specific stock symbol
            $cacheKey = 'stock_price_' . $symbol;
    
            // Check if the stock price is cached
            $cachedStockPrice = Cache::get($cacheKey);
            if ($cachedStockPrice !== null) {
                $results[$symbol] = $cachedStockPrice;
                continue;
            }
    
            $previousDate = Carbon::now()->subDay()->toDateString();
    
            try {
                $response = $this->client->get('https://api.polygon.io/v1/open-close/' . $symbol . '/' . $previousDate, [
                    'query' => [
                        'adjusted' => true,
                        'apiKey' => $this->apiKey
                    ],
                    'timeout' => 10, // Timeout in seconds
                    'retry_on_timeout' => true, // Retry on timeout
                    'http_errors' => false, // Do not throw exceptions for HTTP errors
                ]);
    
                // Check if the request was successful (status code 2xx)
                if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                    $data = json_decode($response->getBody(), true);
    
                    if (isset($data['status']) && $data['status'] == 'OK') {
                        // Cache the fetched stock price for 1 minute
                        Cache::put($cacheKey, $data, Carbon::now()->addMinutes(1));
                        $results[$symbol] = $data;
                    } else {
                        $results[$symbol] = ['error' => 'Invalid response from API'];
                    }
                } elseif ($response->getStatusCode() == 429) {
                    $results[$symbol] = ['error' => 'API rate limit exceeded'];
                } else {
                    $results[$symbol] = ['error' => 'Failed to fetch stock price'];
                }
            } catch (RequestException $e) {
                // Connection issues
                Log::error('Error connecting to Polygon API for symbol ' . $symbol . ': ' . $e->getMessage());
                $results[$symbol] = ['error' => 'Failed to connect to API'];
            } catch (\Exception $e) {
                // Other errors
                Log::error('Error fetching stock price for ' . $symbol . ': ' . $e->getMessage());
                $results[$symbol] = ['error' => 'An error occurred'];
            }
        }
    
        return $results;
    }

    // Insert stock market prices into table every minute
    public function insertStockPrices()
    {
        $previousDate = Carbon::now()->subDay()->toDateString();
        $errors = [];



        foreach ($this->stocks as $symbol) {
            try {
                $response = $this->client->get('https://api.polygon.io/v1/open-close/' . $symbol . '/' . $previousDate, [
                    'query' => [
                        'adjusted' => true,
                        'apiKey' => $this->apiKey
                    ]
                ]);

                // rate limiting
                if ($response->getStatusCode() == 429) {
                    $errors[] = 'API rate limit exceeded for symbol ' . $symbol;
                    continue;
                }

                $data = json_decode($response->getBody(), true);
                $stock = Stock::where('symbol', $data['symbol'])->first();

                if (isset($data['status']) && $data['status'] == 'OK') {
                    // Insert the fetched data into the database
                    StockMarketPrice::create([
                        'symbol' => $stock->id,
                        'date' => $data['from'],
                        'open' => $data['open'],
                        'high' => $data['high'],
                        'low' => $data['low'],
                        'close' => $data['close'],
                        'volume' => $data['volume'],
                        'after_hours' => $data['afterHours'],
                        'pre_market' => $data['preMarket'],
                    ]);
                }
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                // connection issues, retry after a delay
                Log::error('Error connecting to Polygon API for symbol ' . $symbol . ': ' . $e->getMessage());
                $errors[] = 'Error connecting to Polygon API for symbol ' . $symbol . ': ' . $e->getMessage();
                
            } catch (\Exception $e) {
                // other errors
                Log::error('Error fetching stock price for ' . $symbol . ': ' . $e->getMessage());
                $errors[] = 'Error fetching stock price for ' . $symbol . ': ' . $e->getMessage();
            }
        }

        return empty($errors) ? true : $errors;
    }


    //fetch and catche stock prices
    public function fetchAndCacheStockPrices()
    {
        foreach ($this->stocks as $symbol) {
            $this->fetchAndCacheStockPrice($symbol);
        }
    }

    public function fetchAndCacheStockPrice($symbol)
    {
        $cacheKey = "stock_price_{$symbol}";

        if (!Cache::has($cacheKey)) {
            $response = $this->client->get('https://api.polygon.io/v1/open-close/' . $symbol . '/' . Carbon::now()->subDay()->toDateString(), [
                'query' => [
                    'adjusted' => true,
                    'apiKey' => $this->apiKey
                ]
            ]);

            if ($response->getStatusCode() == 429) {
                Log::error('API rate limit exceeded for symbol ' . $symbol);
                return null;
            }

            $data = json_decode($response->getBody(), true);

            if (isset($data['status']) && $data['status'] == 'OK') {
                $latestPrice = $data['close'];
                Cache::put($cacheKey, $latestPrice, now()->addMinutes(1));
            } else {
                Log::error('Error fetching stock price for ' . $symbol . ': ' . json_encode($data));
                return null;
            }
        }

        return Cache::get($cacheKey);
    }

}
