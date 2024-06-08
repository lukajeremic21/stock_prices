<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Prices API Documentation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        h1, h2, h3 {
            color: #333;
        }
        code {
            background-color: #f4f4f4;
            padding: 2px 4px;
            border-radius: 4px;
        }
        pre {
            background-color: #f4f4f4;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <h1>Stock Prices API Documentation</h1>

    <h2>Table of Contents</h2>
    <ol>
        <li><a href="#introduction">Introduction</a></li>
        <li><a href="#environment-configuration">Environment Configuration</a></li>
        <li><a href="#application-structure">Application Structure</a></li>
        <li><a href="#routes">Routes</a></li>
        <li><a href="#services">Services</a></li>
        <li><a href="#models">Models</a></li>
        <li><a href="#how-to-use-the-api">How to Use the API</a></li>
        <li><a href="#custom-commands">Custom Commands</a></li>
    </ol>

    <h2 id="introduction">Introduction</h2>
    <p>The Stock Prices API is built using Laravel and provides endpoints to fetch, insert, and analyze stock prices using the Polygon services.</p>

    <h2 id="environment-configuration">Environment Configuration</h2>
    <p>Before running the application, ensure your <code>.env</code> file is configured correctly:</p>
    <pre><code>
APP_NAME=StockPricesAPI
APP_ENV=local
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=true
APP_URL=http://your-server-ip

DB_CONNECTION=mysql
DB_HOST=your-database-host
DB_PORT=3306
DB_DATABASE=your-database-name
DB_USERNAME=your-database-username
DB_PASSWORD=your-database-password

POLYGON_API_KEY=your-polygon-api-key
    </code></pre>

    <h2 id="application-structure">Application Structure</h2>
    <p>The key components of the application include:</p>
    <ul>
        <li><strong>Controllers:</strong> Handle the HTTP requests and responses.</li>
        <li><strong>Services:</strong> Contain the business logic for interacting with external APIs.</li>
        <li><strong>Models:</strong> Represent the database structure.</li>
    </ul>

    <h2 id="routes">Routes</h2>
    <h3>Route: <code>/api/stock-prices</code></h3>
    <ul>
        <li><strong>Method:</strong> GET</li>
        <li><strong>Description:</strong> Fetch stock prices from the Polygon API.</li>
        <li><strong>Response:</strong> JSON object with stock prices.</li>
    </ul>

    <h3>Route: <code>/api/insert-stock-prices</code></h3>
    <ul>
        <li><strong>Method:</strong> POST</li>
        <li><strong>Description:</strong> Insert stock prices into the database.</li>
        <li><strong>Response:</strong> JSON message indicating success or failure.</li>
    </ul>

    <h3>Route: <code>/api/get-stock-prices-by-symbols</code></h3>
    <ul>
        <li><strong>Method:</strong> POST</li>
        <li><strong>Description:</strong> Get stock prices for specific symbols.</li>
        <li><strong>Parameters:</strong> <code>symbols</code> (array of stock symbols)</li>
        <li><strong>Response:</strong> JSON object with stock prices.</li>
    </ul>

    <h3>Route: <code>/api/get-price-change</code></h3>
    <ul>
        <li><strong>Method:</strong> POST</li>
        <li><strong>Description:</strong> Get price changes for stocks between two dates.</li>
        <li><strong>Parameters:</strong> <code>symbols</code> (array of stock symbols), <code>start_date</code>, <code>end_date</code></li>
        <li><strong>Response:</strong> JSON object with price changes.</li>
    </ul>

    <h3>Route: <code>/api/get-stock-prices-by-date/{symbol}/{date}</code></h3>
    <ul>
        <li><strong>Method:</strong> GET</li>
        <li><strong>Description:</strong> Get stock prices for a specific symbol and date.</li>
        <li><strong>Parameters:</strong> <code>symbol</code> (stock symbol), <code>date</code> (date in YYYY-MM-DD format)</li>
        <li><strong>Response:</strong> JSON object with stock price details.</li>
    </ul>

    <h2 id="services">Services</h2>
    <h3>AlphaVantageService</h3>
    <p>A service to interact with the Alpha Vantage API to fetch stock prices.</p>
    <h4>Methods</h4>
    <ul>
        <li><strong>getStockPrices():</strong> Fetches stock prices for a predefined set of stocks.</li>
    </ul>

    <h3>PolygonService</h3>
    <p>A service to interact with the Polygon API to fetch and manage stock prices.</p>
    <h4>Methods</h4>
    <ul>
        <li><strong>getStockPrices():</strong> Fetches current stock prices.</li>
        <li><strong>insertStockPrices():</strong> Inserts the fetched stock prices into the database.</li>
        <li><strong>getAllLatestStockPrices():</strong> Retrieves the latest stock prices from the database.</li>
        <li><strong>getStockPricesBySymbols(array $symbols):</strong> Fetches stock prices for specified symbols.</li>
        <li><strong>calculatePriceChange(array $symbols, string $startDate, string $endDate):</strong> Calculates price changes between two dates.</li>
    </ul>

    <h2 id="models">Models</h2>
    <h3>Stock</h3>
    <p>Represents the stock entity in the database.</p>

    <h3>StockMarketPrice</h3>
    <p>Represents the stock market price entity in the database.</p>

    <h2 id="how-to-use-the-api">How to Use the API</h2>
    <h3>Fetch Stock Prices</h3>
    <p>To fetch stock prices, make a GET request to <code>/api/stock-prices</code>:</p>
    <pre><code>
curl -X GET http://your-server-ip/api/stock-prices
    </code></pre>

    <h3>Insert Stock Prices</h3>
    <p>To insert stock prices, make a POST request to <code>/api/insert-stock-prices</code>:</p>
    <pre><code>
curl -X POST http://your-server-ip/api/insert-stock-prices
    </code></pre>

    <h3>Get Stock Prices by Symbols</h3>
    <p>To get stock prices for specific symbols, make a POST request to <code>/api/get-stock-prices-by-symbols</code> with a JSON payload:</p>
    <pre><code>
curl -X POST -H "Content-Type: application/json" -d '{"symbols": ["AAPL", "MSFT"]}' http://your-server-ip/api/get-stock-prices-by-symbols
    </code></pre>

    <h3>Get Price Change</h3>
    <p>To get the price change for stocks between two dates, make a POST request to <code>/api/get-price-change</code> with a JSON payload:</p>
    <pre><code>
curl -X POST -H "Content-Type: application/json" -d '{"symbols": ["AAPL", "MSFT"], "start_date": "2023-01-01", "end_date": "2023-06-01"}' http://your-server-ip/api/get-price-change
    </code></pre>

    <h3>Get Stock Prices by Date</h3>
    <p>To get stock prices for a specific symbol and date, make a GET request to <code>/api/get-stock-prices-by-date/{symbol}/{date}</code>:</p>
    <pre><code>
curl -X GET http://your-server-ip/api/get-stock-prices-by-date/AAPL/2023-01-01
    </code></pre>

    <h2 id="custom-commands">Custom Commands</h2>
    <h3>Running Custom Commands</h3>
    <p>Custom commands can be run using the Artisan CLI. For example, if you have a command to update stock prices:</p>
    <pre><code>
php artisan stock:update
    </code></pre>

    <h3>Scheduling Commands</h3>
    <p>You can schedule commands to run periodically by adding them to the Laravel scheduler in <code>app/Console/Kernel.php</code>:</p>
    <pre><code>
protected function schedule(Schedule $schedule)
{
    $schedule->command('stock:update')->hourly();
}
    </code></pre>
    <p>Ensure the Laravel scheduler is running by adding the following Cron entry:</p>
    <pre><code>
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
    </code></pre>
</body>
</html>
